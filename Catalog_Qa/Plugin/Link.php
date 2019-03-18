<?php

namespace Funimation\Catalog\Plugin;


/**
 * Class Link
 */
class Link
{
    protected function _addAttributesToProductCollection(&$collection)
    {
        $collection->addAttributeToSelect(['name', 'image', 'url_key', 'version_type', 'product_format','thumbnail', 'default_category', 'description']);
    }

    public function afterGetRelatedProductCollection($product, $result)
    {
        $this->_addAttributesToProductCollection($result);

        return $result;
    }

    public function afterGetCrossSellProductCollection($product, $result)
    {
        $this->_addAttributesToProductCollection($result);

        return $result;
    }

    public function afterGetUpSellProductCollection($product, $result)
    {
        $this->_addAttributesToProductCollection($result);

        return $result;
    }
}