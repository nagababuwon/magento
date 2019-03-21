<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product type model
 */
namespace Funimation\Catalog\Model\Product;

use Funimation\Catalog\Model\Product;
use Magento\Framework\Data\OptionSourceInterface;

class Type extends \Magento\Catalog\Model\Product\Type
{

    /**
     * Default price model
     */
    const DEFAULT_PRICE_MODEL = 'Funimation\Catalog\Model\Product\Type\Price';

    /**
     * Factory to product singleton product type instances
     *
     * @param   \Funimation\Catalog\Model\Product $product
     * @return  \Magento\Catalog\Model\Product\Type\AbstractType
     */
    public function factory($product)
    {
        $types = $this->getTypes();
        $typeId = $product->getTypeId();

        if (!empty($types[$typeId]['model'])) {
            $typeModelName = $types[$typeId]['model'];
        } else {
            $typeModelName = self::DEFAULT_TYPE_MODEL;
            $typeId = self::DEFAULT_TYPE;
        }

        $typeModel = $this->_productTypePool->get($typeModelName);
        $typeModel->setConfig($types[$typeId]);
        return $typeModel;
    }

    /**
     * Product type price model factory
     *
     * @param   string $productType
     * @return  \Funimation\Catalog\Model\Product\Type\Price
     */
    public function priceFactory($productType)
    {
        if (isset($this->_priceModels[$productType])) {
            return $this->_priceModels[$productType];
        }
        $types = $this->getTypes();
        if (!empty($types[$productType]['price_model'])) {
            $priceModelName = $types[$productType]['price_model'];
        } else {
            $priceModelName = self::DEFAULT_PRICE_MODEL;
        }
        $this->_priceModels[$productType] = $this->_priceFactory->create($priceModelName);
        return $this->_priceModels[$productType];
    }


}
