<?php

namespace Funimation\Catalog\Model\ResourceModel\LinkedProduct;

class Collection
    extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    
    protected function _construct()
    {
        $this->_init('Funimation\Catalog\Model\LinkedProduct', 'Funimation\Catalog\Model\ResourceModel\LinkedProduct');
    }

    public function addFieldToFilter($field, $condition = null)
    {
        parent::addFieldToFilter($field, $condition);
    }
}