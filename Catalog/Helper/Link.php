<?php

/**
 * Class Link
 */

namespace Funimation\Catalog\Helper;

class Link
{
    /** @var \Magento\Catalog\Api\ProductRepositoryInterface  */
    protected $productRepository;

    /** @var \Magento\Catalog\Api\CategoryRepositoryInterface  */
    protected $categoryRepository;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    ){
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }
    
    public function addAdditionalLinkData($link)
    {
        
        $extensionAttrs = $link->getExtensionAttributes();
        $link->setLinkedProductName($extensionAttrs->getName());
        $link->setLinkedProductImage($extensionAttrs->getImage());
        $link->setUrlKey($extensionAttrs->getUrlKey());
        $link->setVersionType($extensionAttrs->getVersionType());
        $link->setProductFormat($extensionAttrs->getProductFormat());
        $link->setLinkedProductThumbnail($extensionAttrs->getThumbnail());
        $link->setLinkedProductDescription($extensionAttrs->getDescription());

        $categoryId = $extensionAttrs->getDefaultCategory();
        if(!empty($categoryId)) {
            $category = $this->categoryRepository->get($categoryId);
            $link->setLinkedProductDefaultCategoryLabel($category->getName());
            $link->setLinkedProductDefaultCategoryUrl($category->getUrlKey());
        }

        $product = $this->productRepository->get($link->getLinkedProductSku());
        $versionType = $product->getAttributeText('version_type');
        $productFormat  = $product->getAttributeText('product_format');
        if ($versionType !== false) {    
            $link->setVersionTypeLabel($versionType);
        }
        if((is_array($productFormat) &&  count($productFormat) > 0))
        {
            $productFormatLabel = implode(',',$productFormat);
            $link->setProductFormatLabel($productFormatLabel);
        }
        
        return $link;
    }
}