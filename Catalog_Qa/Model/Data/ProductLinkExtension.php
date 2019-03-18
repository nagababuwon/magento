<?php
/**
 * Class ProductLinkExtension
 */

namespace Funimation\Catalog\Model\Data;

class ProductLinkExtension
    extends \Magento\Catalog\Api\Data\ProductLinkExtension
{
    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->_get('name');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->setData('name', $name);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->_get('image');
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->setData('image', $image);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlKey()
    {
        return $this->_get('url_key');
    }

    /**
     * @param string $urlKey
     *
     * @return $this
     */
    public function setUrlKey($urlKey)
    {
        $this->setData('url_key', $urlKey);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVersionType()
    {
        return $this->_get('version_type');
    }

    /**
     * @param string $versionType
     *
     * @return $this
     */
    public function setVersionType($versionType)
    {
        $this->setData('version_type', $versionType);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVersionTypeLabel()
    {
        return $this->_get('version_type_label');
    }

    /**
     * @param string $versionTypeLabel
     *
     * @return $this
     */
    public function setVersionTypeLabel($versionTypeLabel)
    {
        $this->setData('version_type_label', $versionTypeLabel);
        return $this;
    }

    /**
     * @param string $productFormat
     *
     * @return $this
     */
    public function setProductFormat($productFormat)
    {
        $this->setData('product_format', $productFormat);
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getProductFormat()
    {
        return $this->_get('product_format');
    }
    
    /**
     * @return string|null
     */
    public function getProductFormatLabel()
    {
        return $this->_get('product_format_label');
    }
    
    /**
     * @param string $productFormatLabel
     *
     * @return $this
     */
    public function setProductFormatLabel($productFormatLabel)
    {
        $this->setData('product_format_label', $productFormatLabel);
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getThumbnail()
    {
        return $this->_get('thumbnail');
    }

    /**
     * @param string $thumbnail
     *
     * @return $this
     */
    public function setThumbnail($thumbnail)
    {
        $this->setData('thumbnail', $thumbnail);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->_get('description');
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->setData('description', $description);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultCategory()
    {
        return $this->_get('defaultCategory');
    }

    /**
     * @param string defaultCategory
     *
     * @return $this
     */
    public function setDefaultCategory($defaultCategory)
    {
        $this->setData('defaultCategory', $defaultCategory);
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getDefaultCategoryUrlKey()
    {
        return $this->_get('defaultCategoryurlkey');
    }

    /**
     * @param string $defaultCategoryUrlKey
     *
     * @return $this
     */
    public function setDefaultCategoryUrlKey($defaultCategoryUrlKey)
    {
        $this->setData('defaultCategoryurlkey', $defaultCategoryUrlKey);
        return $this;
    }    
}
