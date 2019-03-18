<?php

namespace Funimation\Catalog\Plugin;


/**
 * Class Attribute
 */
class Attribute
{

    /** @var \Funimation\Catalog\Model\Cache\Attribute\Type  */
    protected $attributeCacheType;


    public function __construct(\Funimation\Catalog\Model\Cache\Attribute\Type $attributeCacheType)
    {
        $this->attributeCacheType = $attributeCacheType;
    }

    public function afterAfterSave($subject, $result)
    {
        $this->attributeCacheType->clean();
        return $result;
    }

}