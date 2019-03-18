<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Funimation\Catalog\Model\Attribute;

/**
 *
 * This is a parent class for storing information about attribute source option text
 *
 */
class Source
{


    const CACHE_ID = "attribute_source";

    /** @var \Funimation\Catalog\Model\Cache\Attribute\Type  */
    protected $attributeCacheType;

    /** @var \Magento\Catalog\Model\ResourceModel\Product  */
    protected $resourceModel;


    public function __construct(\Funimation\Catalog\Model\Cache\Attribute\Type $attributeCacheType,
                                \Magento\Catalog\Model\ResourceModel\Product $resourceModel
                                )
    {
        $this->attributeCacheType = $attributeCacheType;
        $this->resourceModel = $resourceModel;
    }

    public function getAttributeSourceText(\Magento\Framework\Api\AttributeValue $attribute) {
        $optionText=null;

        if(!is_array($attribute->getValue()) && $attribute->getValue()) {

            $optionText = $this->attributeCacheType->load(self::CACHE_ID.'-'.$attribute->getAttributeCode().'-'.$attribute->getValue());
            if ($optionText && is_string($optionText)) {
                $optionText = unserialize($optionText);
            } else {
                $attributeEntity = $this->resourceModel->getAttribute($attribute->getAttributeCode());

                if ( $attributeEntity->usesSource()) {

                    $optionText = $attributeEntity->getSource()->getOptionText($attribute->getValue());

                    $this->attributeCacheType->save(serialize($optionText),
                        self::CACHE_ID.'-'.$attribute->getAttributeCode().'-'.$attribute->getValue(),
                        [\Funimation\Catalog\Model\Cache\Attribute\Type::CACHE_TAG]);
                }
            }
        }
        return $optionText;
    }

    public function clean() {
        $this->attributeCacheType->clean(\Zend_Cache::CLEANING_MODE_ALL,
            [\Funimation\Catalog\Model\Cache\Attribute\Type::CACHE_TAG]
        );
    }
}
