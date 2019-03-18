<?php

namespace Funimation\Catalog\Api\Data;


/**
 * Class ProductLinkInterface
 */
interface ProductLinkInterface
    extends \Magento\Catalog\Api\Data\ProductLinkInterface
{
    /**
     * Get linked product name
     *
     * @return string
     */
    public function getLinkedProductName();

    /**
     * Set linked product name
     *
     * @param string $linkedProductName
     * @return $this
     */
    public function setLinkedProductName($linkedProductName);

    /**
     * Get linked product image
     *
     * @return string
     */
    public function getLinkedProductImage();

    /**
     * Set linked product image
     *
     * @param string $linkedProductImage
     * @return $this
     */
    public function setLinkedProductImage($linkedProductImage);

    /**
     * @return string
     */
    public function getLinkedProductThumbnail();

    /**
     * @param string $linkedProductThumbnail
     *
     * @return $this
     */
    public function setLinkedProductThumbnail($linkedProductThumbnail);

    /**
     * @return string
     */
    public function getLinkedProductDescription();

    /**
     * @param string $linkedProductDescription
     *
     * @return $this
     */
    public function setLinkedProductDescription($linkedProductDescription);

    /**
     * @return string
     */
    public function getLinkedProductDefaultCategoryLabel();

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLinkedProductDefaultCategoryLabel($label);

    /**
     * @return string
     */
    public function getLinkedProductDefaultCategoryUrl();

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setLinkedProductDefaultCategoryUrl($url);

    /**
     * Get product URL key.
     *
     * @return string
     */
    public function getUrlKey();

    /**
     * Set product URL key.
     *
     * @param string $urlKey
     *
     * @return $this
     */
    public function setUrlKey($urlKey);

    /**
     * Get version type.
     *
     * @return string
     */
    public function getVersionType();

    /**
     * Set version type.
     *
     * @param string $versionType
     *
     * @return $this
     */
    public function setVersionType($versionType);
    
    /**
     * Get version type label.
     *
     * @return string
     */
    public function getVersionTypeLabel();

    /**
     * Set version type label.
     *
     * @param string $versionTypeLabel
     *
     * @return $this
     */
    public function setVersionTypeLabel($versionTypeLabel);
    
    /**
     * Set product format.
     *
     * @param string $productFormat
     *
     * @return $this
     */
    public function setProductFormat($productFormat);
    
    /**
     * Get product format label.
     *
     * @return string
     */
    public function getProductFormat();
    
    /**
     * Set product format label.
     *
     * @param string $productFormatLabel
     *
     * @return $this
     */
    public function setProductFormatLabel($productFormatLabel);
    
    /**
     * Get product format label.
     *
     * @return string
     */
    public function getProductFormatLabel();
}
