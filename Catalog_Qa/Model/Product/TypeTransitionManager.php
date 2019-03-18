<?php

namespace Funimation\Catalog\Model\Product;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
/**
 * Class TypeTransitionManager
 */
class TypeTransitionManager extends \Magento\Catalog\Model\Product\TypeTransitionManager
{



    public function processProduct(Product $product)
    {
        if (in_array($product->getTypeId(), $this->compatibleTypes)) {
            $currentTypeId = $product->getTypeId();
            $product->setTypeInstance(null);
            $productTypeId = $this->weightResolver->resolveProductHasWeight($product)
                ? $currentTypeId
                : Type::TYPE_VIRTUAL;
            $product->setTypeId($productTypeId);
        }
    }

}


?>