<?php

namespace Funimation\Catalog\Model\Data;

use Funimation\Catalog\Api\Data\FilterOptionInterface;
use Magento\Framework\Api\AbstractExtensibleObject;


/**
 * Class Filter
 */
class FilterOption
    extends AbstractExtensibleObject
    implements FilterOptionInterface
{
    const KEY_VALUE = 'value';
    const KEY_LABEL = 'label';
    const KEY_PRODUCT_COUNT = 'product_count';

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        return $this->setData(self::KEY_VALUE, $value);
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->_get(self::KEY_VALUE) === null ? [] : $this->_get(self::KEY_VALUE);
    }

    /**
     * @inheritdoc
     */
    public function setLabel($label)
    {
        return $this->setData(self::KEY_LABEL, $label);
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->_get(self::KEY_LABEL) === null ? [] : $this->_get(self::KEY_LABEL);
    }

    /**
     * @inheritdoc
     */
    public function setProductCount($productCount)
    {
        return $this->setData(self::KEY_PRODUCT_COUNT, $productCount);
    }

    /**
     * @inheritdoc
     */
    public function getProductCount()
    {
        return $this->_get(self::KEY_PRODUCT_COUNT) === null ? [] : $this->_get(self::KEY_PRODUCT_COUNT);
    }
}
