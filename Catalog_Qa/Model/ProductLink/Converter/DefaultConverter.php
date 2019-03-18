<?php
namespace Funimation\Catalog\Model\ProductLink\Converter;

use Magento\Catalog\Model\ProductLink\Converter\ConverterInterface;

/**
 * Class DefaultConverter
 */
class DefaultConverter
    implements ConverterInterface
{
    /** @var \Magento\Catalog\Helper\Product */
    protected $productHelper;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepo;

    /**
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     */
    public function __construct(
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Framework\View\Asset\Repository $assetRepo
    )
    {
        $this->productHelper = $productHelper;
        $this->assetRepo = $assetRepo;
    }

    public function convert(\Magento\Catalog\Model\Product $product)
    {
        return [
            'type' => $product->getTypeId(),
            'sku' => $product->getSku(),
            'position' => $product->getPosition(),
            'custom_attributes' => [
                ['attribute_code' => 'name', 'value' => $product->getName()],
                ['attribute_code' => 'image', 'value' => $this->productHelper->getImageUrl($product)],
                ['attribute_code' => 'urlKey', 'value' => $product->getUrlKey()],
                ['attribute_code' => 'versionType', 'value' => $product->getVersionType()],
                ['attribute_code' => 'productFormat', 'value' => $product->getProductFormat()],
                ['attribute_code' => 'thumbnail', 'value' =>  $this->getProductThumbnailUrl($product)],
                ['attribute_code' => 'description', 'value' => $product->getDescription()],
                ['attribute_code' => 'defaultCategory', 'value' => $product->getDefaultCategory()],
            ]
        ];
    }

    protected function getProductThumbnailUrl($product)
    {
        $url = false;
        $attribute = $product->getResource()->getAttribute('thumbnail');
        if (!$product->getThumbnail()) {
            $url = $this->assetRepo->getUrl('Magento_Catalog::images/product/placeholder/thumbnail.jpg');
        } elseif ($attribute) {
            $url = $attribute->getFrontend()->getUrl($product);
        }
        return $url;
    }
}