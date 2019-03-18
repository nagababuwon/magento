<?php
namespace Funimation\Catalog\Plugin;

class ConfigurableOptions
{
    public function afterGetPrice($product, $result)
    {
        if($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE)
        {
            $usedProducts = $product->getTypeInstance()->getUsedProducts($product);
            if(is_array($usedProducts) && count($usedProducts) > 0){
                $firstChildProduct = $usedProducts[0];
                if($firstChildProduct){
                    $result = $firstChildProduct->getPrice();
                }
            }
        }
        return $result;
    }
    
    public function afterGetSpecialPrice($product, $result)
    {
        if($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE)
        {
            $usedProducts = $product->getTypeInstance()->getUsedProducts($product);
            if(is_array($usedProducts) && count($usedProducts) > 0){
                $firstChildProduct = $usedProducts[0];
                if($firstChildProduct){
                    $result = $firstChildProduct->getSpecialPrice();
                }
            }
        }
        return $result;
    }
}

