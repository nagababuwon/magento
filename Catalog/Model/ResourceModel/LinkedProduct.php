<?php

namespace Funimation\Catalog\Model\ResourceModel;

class LinkedProduct
    extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('catalog_product_link','link_id');
    }
}