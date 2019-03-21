<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Funimation\Bundle\Model\Product;

use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Funimation\Catalog\Api\Data\ProductTierPriceExtensionFactory;
use Magento\Framework\App\ObjectManager;

class Price extends \Magento\Bundle\Model\Product\Price {

    /**
     * @var ProductTierPriceExtensionFactory
     */
    private $tierPriceExtensionFactory;

    public function __construct(
        \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        PriceCurrencyInterface $priceCurrency,
        GroupManagementInterface $groupManagement,
        \Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory $tierPriceFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Catalog\Helper\Data $catalogData,
        ProductTierPriceExtensionFactory $tierPriceExtensionFactory = null
    )
    {
        parent::__construct($ruleFactory,
            $storeManager,
            $localeDate,
            $customerSession,
            $eventManager,
            $priceCurrency,
            $groupManagement,
            $tierPriceFactory,
            $config,
            $catalogData
        );
        $this->tierPriceExtensionFactory = $tierPriceExtensionFactory ?: ObjectManager::getInstance()
            ->get(ProductTierPriceExtensionFactory::class);

    }

    /**
     * Return product base price
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return float
     */
    public function getPrice($product)
    {
        if ($product->getPriceType() == self::PRICE_TYPE_FIXED) {
            return $product->getData('price');
        } else {
            return $product->getData('price');
        }
    }

    /**
     * Get product tier price by qty
     *
     * @param   float                    $qty
     * @param   \Magento\Catalog\Model\Product $product
     * @return  float|array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getTierPrice($qty, $product)
    {

        $allCustomersGroupId = $this->_groupManagement->getAllCustomersGroup()->getId();
        $prices = $product->getData('tier_price');

        if ($prices === null) {
            if ($attribute = $product->getResource()->getAttribute('tier_price')) {
                $attribute->getBackend()->afterLoad($product);
                $prices = $product->getData('tier_price');
            }
        }

        if ($prices === null || !is_array($prices)) {
            if ($qty !== null) {
                return $product->getPrice();
            }
            return [
                [
                    'price' => $product->getPrice(),
                    'website_price' => $product->getPrice(),
                    'price_qty' => 1,
                    'cust_group' => $allCustomersGroupId,
                ]
            ];
        }

        $custGroup = $this->_getCustomerGroupId($product);
        if ($qty) {
            $prevQty = 1;
            $prevPrice = 0;
            $prevGroup = $allCustomersGroupId;

            foreach ($prices as $price) {
                if (empty($price['percentage_value'])) {
                    // can use only percentage tier price
                    continue;
                }
                if ($price['cust_group'] != $custGroup && $price['cust_group'] != $allCustomersGroupId) {
                    // tier not for current customer group nor is for all groups
                    continue;
                }
                if ($qty < $price['price_qty']) {
                    // tier is higher than product qty
                    continue;
                }
                if ($price['price_qty'] < $prevQty) {
                    // higher tier qty already found
                    continue;
                }
                if ($price['price_qty'] == $prevQty
                    && $prevGroup != $allCustomersGroupId
                    && $price['cust_group'] == $allCustomersGroupId
                ) {
                    // found tier qty is same as current tier qty but current tier group is ALL_GROUPS
                    continue;
                }

                if ($price['percentage_value'] > $prevPrice) {
                    $prevPrice = $price['percentage_value'];
                    $prevQty = $price['price_qty'];
                    $prevGroup = $price['cust_group'];
                }
            }

            return $prevPrice;
        } else {
            $qtyCache = [];
            foreach ($prices as $i => $price) {
                if ($price['cust_group'] != $custGroup && $price['cust_group'] != $allCustomersGroupId) {
                    unset($prices[$i]);
                } elseif (isset($qtyCache[$price['price_qty']])) {
                    $j = $qtyCache[$price['price_qty']];
                    if ($prices[$j]['website_price'] < $price['website_price']) {
                        unset($prices[$j]);
                        $qtyCache[$price['price_qty']] = $i;
                    } else {
                        unset($prices[$i]);
                    }
                } else {
                    $qtyCache[$price['price_qty']] = $i;
                }
            }
        }

        return $prices ? $prices : [];
    }
    
    /**
     * Calculate final price of selection
     * with take into account tier price
     *
     * @param  \Magento\Catalog\Model\Product $bundleProduct
     * @param  \Magento\Catalog\Model\Product $selectionProduct
     * @param  float                    $bundleQty
     * @param  float                    $selectionQty
     * @param  bool                       $multiplyQty
     * @param  bool                       $takeTierPrice
     * @return float
     */
    public function getSelectionFinalTotalPrice(
        $bundleProduct,
        $selectionProduct,
        $bundleQty,
        $selectionQty,
        $multiplyQty = true,
        $takeTierPrice = true
    ) {
        if (null === $bundleQty) {
            $bundleQty = 1.;
        }
        if ($selectionQty === null) {
            $selectionQty = $selectionProduct->getSelectionQty();
        }

        if ($bundleProduct->getPriceType() == self::PRICE_TYPE_DYNAMIC) {

            //* always get the option price from the selection price value **/
            if ($selectionProduct->hasSelectionPriceValue()) {
                $price = $selectionProduct->getSelectionPriceValue();

            } else {
                $price = $selectionProduct->getFinalPrice($takeTierPrice ? $selectionQty : 1);

                // if not, get it from the selected product
                $optionsIds = $bundleProduct->getTypeInstance()->getOptionsIds($bundleProduct);
                foreach ($bundleProduct->getTypeInstance()->getSelectionsCollection($optionsIds, $bundleProduct) as $selection) {
                    if ($selection->getSku() == $selectionProduct->getSku())
                        $price = $selection->getSelectionPriceValue();
                }

            }


            //\Magento\Framework\App\ObjectManager::getInstance()
               // ->get('Psr\Log\LoggerInterface')->debug($selectionProduct->getSku() .' '.$selectionProduct->getSelectionPriceValue(). ' '.$price);
        } else {
            if ($selectionProduct->getSelectionPriceType()) {
                // percent
                $product = clone $bundleProduct;
                $product->setFinalPrice($this->getPrice($product));
                $this->_eventManager->dispatch(
                    'catalog_product_get_final_price',
                    ['product' => $product, 'qty' => $bundleQty]
                );
                $price = $product->getData('final_price') * ($selectionProduct->getSelectionPriceValue() / 100);
            } else {
                // fixed
                $price = $selectionProduct->getSelectionPriceValue();
            }
        }

        if ($multiplyQty) {
            $price *= $selectionQty;
        }

        return min(
            $price,
            $this->_applyTierPrice($bundleProduct, $bundleQty, $price),
            $this->_applySpecialPrice($bundleProduct, $price)
        );
    }

    /**
     * Gets list of product tier prices
     *
     * @param Product $product
     * @return \Funimation\Catalog\Api\Data\ProductTierPriceInterface[]
     */
    public function getTierPrices($product)
    {
        $prices = [];
        $tierPrices = $this->getExistingPrices($product, 'tier_price');

        foreach ($tierPrices as $price) {
            /** @var \Funimation\Catalog\Api\Data\ProductTierPriceInterface $tierPrice */
            $tierPrice = $this->tierPriceFactory->create()
                ->setExtensionAttributes($this->tierPriceExtensionFactory->create());
            $tierPrice->setCustomerGroupId($price['cust_group']);
            if (array_key_exists('website_price', $price)) {
                $value = $price['website_price'];
            } else {
                $value = $price['price'];
            }
            $tierPrice->setValue($value);
            $tierPrice->setQty($price['price_qty']);
            if (isset($price['percentage_value'])) {
                $tierPrice->getExtensionAttributes()->setPercentageValue($price['percentage_value']);
            }
            $websiteId = isset($price['website_id']) ? $price['website_id'] : $this->getWebsiteForPriceScope();
            $tierPrice->getExtensionAttributes()->setWebsiteId($websiteId);
            $prices[] = $tierPrice;
        }
        return $prices;
    }

}

?>