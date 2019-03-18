<?php
namespace Funimation\Catalog\Plugin;

class ConfigurablePriceResolve
{   
    public function aroundResolvePrice($subject, $proceed, \Magento\Framework\Pricing\SaleableInterface $product)
    {
        $price = null;

        foreach ($product->getTypeInstance()->getUsedProducts($product) as $subProduct) {
            $productPrice = $subProduct->getFinalPrice();
            $price = $price ? min($price, $productPrice) : $productPrice;
        }

        return (float)$price;
    }
    
}

