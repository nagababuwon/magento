<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Funimation\Bundle\Model\Product;

class Price extends \Magento\Bundle\Model\Product\Price {



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

}

?>