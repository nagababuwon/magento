<?php
/**
 * Class ProductLinkManagement
 */

namespace Funimation\Catalog\Model;

use  Funimation\Catalog\Api\ProductLinkManagementInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductLinkManagement
    implements ProductLinkManagementInterface
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Catalog\Model\Product\LinkTypeProvider
     */
    protected $linkTypeProvider;

    protected $linkHelper;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Model\Product\LinkTypeProvider $linkTypeProvider
     */
    public function __construct(
        \Funimation\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\Product\LinkTypeProvider $linkTypeProvider,
        \Funimation\Catalog\Helper\Link $linkHelper
    ) {
        $this->productRepository = $productRepository;
        $this->linkTypeProvider = $linkTypeProvider;
        $this->linkHelper = $linkHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getLinkedItemsByType($sku, $type)
    {
        $output = [];

        $linkTypes = $this->linkTypeProvider->getLinkTypes();

        if (!isset($linkTypes[$type])) {
            throw new NoSuchEntityException(__('Unknown link type: %1', (string)$type));
        }
        $product = $this->productRepository->get($sku);
        $links = $product->getProductLinks();

        // Only return the links of type specified
        foreach ($links as $link) {
            if ($link->getLinkType() == $type) {
                $link = $this->linkHelper->addAdditionalLinkData($link);
                $output[] = $link;
            }
        }

        return $output;
    }
}