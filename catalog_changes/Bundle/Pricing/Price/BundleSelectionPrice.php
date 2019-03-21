<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Funimation\Bundle\Pricing\Price;

use Magento\Bundle\Model\Product\Price;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price as CatalogPrice;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Price\AbstractPrice;
/**
 * Bundle option price
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BundleSelectionPrice extends \Magento\Bundle\Pricing\Price\BundleSelectionPrice
{


    /**
     * Get the price value for one of selection product
     *
     * @return bool|float
     */
    public function getValue()
    {


        if (null !== $this->value) {
            return $this->value;
        }

        $priceCode = $this->useRegularPrice ? \Magento\Bundle\Pricing\Price\BundleRegularPrice::PRICE_CODE : \Magento\Bundle\Pricing\Price\FinalPrice::PRICE_CODE;
        if ($this->bundleProduct->getPriceType() == Price::PRICE_TYPE_DYNAMIC) {
            // just return whatever the product's value is
            /*
            $value = $this->priceInfo
                ->getPrice($priceCode)
                ->getValue();
            */
           // calculate price for selection type fixed
           $selectionPriceValue = $this->selection->getSelectionPriceValue();
           $value = $this->priceCurrency->convert($selectionPriceValue) * $this->quantity;
        } else {
            // don't multiply by quantity.  Instead just keep as quantity = 1
            $selectionPriceValue = $this->selection->getSelectionPriceValue();
            if ($this->product->getSelectionPriceType()) {
                // calculate price for selection type percent
                $price = $this->bundleProduct->getPriceInfo()
                    ->getPrice(CatalogPrice\RegularPrice::PRICE_CODE)
                    ->getValue();
                $product = clone $this->bundleProduct;
                $product->setFinalPrice($price);
                $this->eventManager->dispatch(
                    'catalog_product_get_final_price',
                    ['product' => $product, 'qty' => $this->bundleProduct->getQty()]
                );
                $value = $product->getData('final_price') * ($selectionPriceValue / 100);
            } else {
                // calculate price for selection type fixed
                $value = $this->priceCurrency->convert($selectionPriceValue) * $this->quantity;
            }
        }
        if (!$this->useRegularPrice) {
            $value = $this->discountCalculator->calculateDiscount($this->bundleProduct, $value);
        }
        $this->value = $this->priceCurrency->round($value);

        return $this->value;
    }



    /**
     * @return SaleableInterface
     */
    public function getProduct()
    {

        if ($this->bundleProduct->getPriceType() == Price::PRICE_TYPE_DYNAMIC) {
            return parent::getProduct();
        } else {
            return $this->bundleProduct;
        }
    }
}
