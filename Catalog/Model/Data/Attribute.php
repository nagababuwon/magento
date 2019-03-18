<?php
/**
 * Class Attribute
 */

namespace Funimation\Catalog\Model\Data;

use \Funimation\Catalog\Api\Data\AttributeInterface;
use \Magento\Framework\Api\AttributeValue;

class Attribute
    extends AttributeValue
    implements AttributeInterface
{
    public function getAttributeLabel()
    {
        return $this->_get(self::ATTRIBUTE_LABEL);
    }

    public function setAttributeLabel($attributeLabel)
    {
        $this->_data[self::ATTRIBUTE_LABEL] = $attributeLabel;
        return $this;
    }
}