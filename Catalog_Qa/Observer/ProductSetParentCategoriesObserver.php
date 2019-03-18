<?php

namespace Funimation\Catalog\Observer;

use Magento\Catalog\Model\Product;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\CacheInterface;

class ProductSetParentCategoriesObserver implements ObserverInterface
{
    const XML_PATH_CATEGORY_PARENT_FIELD = 'catalog/category_parent/auto_set_category_parent';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
                                \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                                CacheInterface $cache)
    {
        $this->categoryRepository = $categoryRepository;
        $this->scopeConfig = $scopeConfig;
        $this->cache = $cache;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->getConfigValue(self::XML_PATH_CATEGORY_PARENT_FIELD)) {
            /** @var Product $product */
            $product = $observer->getEvent()->getProduct();

            $categories = $product->getCategoryIds();
            foreach ($categories as $categoryId){
                $categories = array_merge($categories, $this->getParentIds($categoryId));
            }

            $categories = array_unique($categories);
            $index = array_search(\Magento\Catalog\Model\Category::TREE_ROOT_ID,array_unique($categories));
            unset($categories[$index]);
            $product->setCategoryIds($categories);
        }
        $cache_status = $this->scopeConfig->getValue('funimation_webapi/webapi/cache', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($cache_status){
            /** @var Product $product */
            $product = $observer->getEvent()->getProduct();
            $sku = $product->getSku();
            $cacheKey = 'PRODUCTREPOSITORYINTERFACE_GET_PRODUCTS_'.$sku;
            $this->cache->remove($cacheKey);
            $productListingTag = 'ProductRepositoryInterface_getList';
            $this->cache->clean($productListingTag);
        }
    }

    /**
     * {@inheritdoc}
     */
    private function getParentIds($categoryId)
    {
        $result = [];
        if ($categoryId !== null) {
            /** @var \Magento\Catalog\Model\Category $category */
            $result =  $this->categoryRepository->get($categoryId)->getParentIds();
        }
        return $result;
    }

    /**
     * Return store configuration value
     *
     * @param string $path
     * @return bool
     */
    private function getConfigValue($path)
    {
        return $this->scopeConfig->getValue($path);
    }
}
