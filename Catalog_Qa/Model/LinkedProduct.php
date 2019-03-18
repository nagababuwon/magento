<?php

namespace Funimation\Catalog\Model;

class LinkedProduct
    extends \Magento\Framework\Model\AbstractModel 
    implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'linked_product_item';
    const KEY_LINK_ID = 'link_id';
    const KEY_PRODUCT_ID = 'product_id';
    const KEY_LINKED_PRODUCT_ID = 'linked_product_id';
    const KEY_LINK_TYPE_ID = 'link_type_id';
 
    protected function _construct()
    {
        $this->_init('Funimation\Catalog\Model\ResourceModel\LinkedProduct');
    }
 
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getLinkId()
    {
        return $this->getData(self::KEY_LINK_ID) === null ? [] : $this->getData(self::KEY_LINK_ID);
    }
    
    public function setLinkId($linkId)
    {
        return $this->setData(self::KEY_LINK_ID, $linkId);
    }
    
    public function getProductId()
    {
        return $this->getData(self::KEY_PRODUCT_ID) === null ? [] : $this->getData(self::KEY_PRODUCT_ID);
    }
    
    public function setProductId($productId)
    {
        return $this->setData(self::KEY_PRODUCT_ID, $productId);
    }
    
    public function getLinkedProductId()
    {
        return $this->getData(self::KEY_LINKED_PRODUCT_ID) === null ? [] : $this->getData(self::KEY_LINKED_PRODUCT_ID);
    }
    
    public function setLinkedProductId($linkedProductId)
    {
        return $this->setData(self::KEY_LINKED_PRODUCT_ID, $linkedProductId);
    }
    
    public function getLinkTypeId()
    {
        return $this->getData(self::KEY_LINK_TYPE_ID) === null ? [] : $this->getData(self::KEY_LINK_TYPE_ID);
    }
    
    public function setLinkTypeId($linkTypeId)
    {
        return $this->setData(self::KEY_LINK_TYPE_ID, $linkTypeId);
    }    
}