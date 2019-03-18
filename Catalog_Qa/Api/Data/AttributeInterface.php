<?php
/**
 * Class AttributeInterface
 */

namespace Funimation\Catalog\Api\Data;


interface AttributeInterface
    extends \Magento\Framework\Api\AttributeInterface
{
    const ATTRIBUTE_LABEL = 'label';

    /**
     * Get attribute label
     *
     * @return string
     */
    public function getAttributeLabel();

    /**
     * Set attribute label
     *
     * @param string $attributeLabel
     * @return $this
     */
    public function setAttributeLabel($attributeLabel);
}