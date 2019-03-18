<?php
/**
 * Class Filter
 */

namespace Funimation\Catalog\Model\Data;

use Funimation\Catalog\Api\Data\FilterInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class Filter
    extends AbstractExtensibleObject
    implements FilterInterface
{
    const KEY_ATTRIBUTE_CODE = 'attribute_code';
    const KEY_LABEL = 'label';
    const KEY_OPTIONS = 'options';

    const KEY_SHOW_EMPTY = 'show_empty';

    /**
     * @inheritdoc
     */
    public function setAttributeCode($attributeCode)
    {
        return $this->setData(self::KEY_ATTRIBUTE_CODE, $attributeCode);
    }

    /**
     * @inheritdoc
     */
    public function getAttributeCode()
    {
        return $this->_get(self::KEY_ATTRIBUTE_CODE) === null ? [] : $this->_get(self::KEY_ATTRIBUTE_CODE);
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
    public function setOptions(array $options)
    {
        return $this->setData(self::KEY_OPTIONS, $options);
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return $this->_get(self::KEY_OPTIONS) === null ? [] : $this->_get(self::KEY_OPTIONS);
    }

    /**
     * @inheritdoc
     */
    public function addOption($option)
    {
        $options = $this->getOptions();
        $options[$option->getValue()] = $option;

        return $this->setOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function removeOption($value)
    {
        $options = $this->getOptions();
        unset($options[$value]);

        return $this->setOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function getOptionByValue($value)
    {
        $options = $this->getOptions();

        return isset($options[$value]) ? $options[$value] : null;
    }

    /**
     * @inheritdoc
     */
    public function setShowEmpty($showEmpty)
    {
        return $this->setData(self::KEY_SHOW_EMPTY, $showEmpty);
    }

    /**
     * @inheritdoc
     */
    public function getShowEmpty()
    {
        return $this->_get(self::KEY_SHOW_EMPTY) === null ? [] : $this->_get(self::KEY_SHOW_EMPTY);
    }
}