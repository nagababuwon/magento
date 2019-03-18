<?php

namespace Funimation\Catalog\Api\Data;

/**
 * @api
 */
interface CategoryTreeInterface
    extends \Magento\Catalog\Api\Data\CategoryTreeInterface
{
    /**
     * @return bool|null
     */
    public function getIncludeInMenu();

    /**
     * @param bool $includeInMenu
     * @return $this
     */
    public function setIncludeInMenu($includeInMenu);
    
    /**
     * @return \Funimation\Catalog\Api\Data\CategoryTreeInterface[]
     */
    public function getChildrenData();

    /**
     * @param \Funimation\Catalog\Api\Data\CategoryTreeInterface[] $childrenData
     * @return $this
     */
    public function setChildrenData(array $childrenData = null);

    /**
     * @return string|null
     */
    public function  getMetaTitle();
    
    /**
     * @param string $metaTitle
     * @return $this
     */
    public function setMetaTitle($metaTitle);
    
    /**
     * @return string|null
     */
    public function getMetaKeywords();
    
    /**
     * @param string $metaKeywords
     * @return $this
     */
    public function setMetaKeywords($metaKeywords);
    
    /**
     * @return string|null
     */
    public function getMetaDescription();
    
    /**
     * @param string $metaDescription
     * @return $this
     */
    public function setMetaDescription($metaDescription);
}