<?php
namespace Funimation\Catalog\Model;

use \Funimation\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use \Magento\Catalog\Model\ResourceModel\Product\Collection;
use \Magento\Framework\Api\SortOrder;
use \Funimation\Catalog\Api\Data\FilterInterface;
use Magento\Framework\Exception\NoSuchEntityException;


/**
 * Class ProductRepository
 */
class ProductRepository extends \Magento\Catalog\Model\ProductRepository
    implements ProductRepositoryInterface
{
    /** @var \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var \Funimation\Catalog\Api\Data\FilterInterfaceFactory */
    protected $filtersFactory;

    /** @var \Funimation\Catalog\Api\Data\FilterOptionInterfaceFactory */
    protected $filtersOptionsFactory;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory */
    protected $collectionFactory;

    /** @var \Magento\Framework\Api\SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var \Magento\Catalog\Api\ProductAttributeRepositoryInterface */
    protected $metadataService;

    /** @var \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface */
    protected $extensionAttributesJoinProcessor;

    /** @var \Magento\Catalog\Model\Layer\FilterableAttributeListInterface */
    protected $filterableAttributes;

    /** @var \Magento\Catalog\Model\ProductFactory  */
    protected $productFactory;

    /** @var \Magento\Catalog\Model\ResourceModel\Product  */
    protected $resourceModel;

    /** @var \Funimation\Catalog\Model\Data\AttributeFactory  */
    protected $attributeFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $storeManager;

    /**
     * @var \Magento\Review\Model\Review\SummaryFactory 
     */
    protected $summaryFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;
    
    /**
     * @var \Funimation\SizeChart\Api\SizeChartInterface 
     */
    protected $sizeChartModelFactory;
    
    /**
     * @var \Funimation\SizeChart\Model\Data\SizeChartFactory 
     */
    protected $sizeChart;

    /** @var CategoryRepositoryInterface  */
    protected $categoryRepository;

    /** @var \Funimation\Catalog\Helper\Link  */
    protected $linkHelper;

    /** @var \Funimation\Catalog\Model\Attribute\Source  */
    protected $attributeSource;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $stockRegistry;

    /**
     * @var \Magento\Catalog\Api\Data\ProductExtensionFactory
     */
    protected $productExtensionFactory;

    /**
     * @var \Magento\CatalogRule\Model\Rule;
     */
    protected $rule;
    /**
     * @var \Magento\Customer\Model\Session;
     */
    protected $customerSession;

    /*
     * @var array
     */
    protected $filterAttributesToBeValidated = array('show_name', 'show_ids', 'upc');

    const EMPTY_FILTER_VALUE = 'ENTER_SEARCH_TERM_HERE';

    public function __construct(
        \Funimation\Catalog\Api\Data\ProductLayeredResultsInterfaceFactory $searchResultsFactory,
        \Funimation\Catalog\Api\Data\FilterInterfaceFactory $filtersFactory,
        \Funimation\Catalog\Api\Data\FilterOptionInterfaceFactory $filtersOptionsFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $metadataService,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        \Magento\Catalog\Model\Layer\FilterableAttributeListInterface $filterableAttributes,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product $resourceModel,
        \Funimation\Catalog\Model\Data\AttributeFactory $attributeFactory,
        \Magento\Review\Model\Review\SummaryFactory $summaryFactory,    
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Funimation\SizeChart\Model\SizeChartFactory $sizeChartModelFactory,
        \Funimation\SizeChart\Api\Data\SizeChartInterface $sizeChart,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Funimation\Catalog\Helper\Link $linkHelper,
        \Funimation\Catalog\Model\Attribute\Source $attributeSource,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Catalog\Api\Data\ProductExtensionFactory $productExtensionFactory,
        \Magento\CatalogRule\Model\Rule $rule,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->filtersFactory = $filtersFactory;
        $this->filtersOptionsFactory = $filtersOptionsFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->metadataService = $metadataService;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->filterableAttributes = $filterableAttributes;
        $this->productFactory = $productFactory;
        $this->resourceModel = $resourceModel;
        $this->attributeFactory = $attributeFactory;
        $this->storeManager = $storeManager;
        $this->summaryFactory = $summaryFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->sizeChartModelFactory = $sizeChartModelFactory;
        $this->sizeChart = $sizeChart;
        $this->categoryRepository = $categoryRepository;
        $this->linkHelper = $linkHelper;
        $this->attributeSource = $attributeSource;
        $this->stockRegistry = $stockRegistry;
        $this->productExtensionFactory = $productExtensionFactory;
        $this->customerSession = $customerSession;
        $this->rule = $rule;
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        return $this->_getList($searchCriteria, function ($collection, $searchCriteria) {

            $sortOrderByPopularity = null;
            foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                if (in_array($field, [
                    \Funimation\Catalog\Api\Data\ProductInterface::RATING_SUMMARY,
                    \Funimation\Catalog\Api\Data\ProductInterface::REVIEWS_COUNT
                ])) {
                    $sortOrderByPopularity = $sortOrder;
                    break;
                }
            }

            // When the request specifies sorting by rating summary or rating (review) count, the fields for popularity
            // are added to the collection.
            if ($sortOrderByPopularity !== null) {
                $storeId = $this->storeManager->getStore()->getId();

                // Add rating (popularity) information
                $collection->joinTable(
                    'review_entity_summary',
                    'entity_pk_value = entity_id',
                    [
                        \Funimation\Catalog\Api\Data\ProductInterface::RATING_SUMMARY,
                        \Funimation\Catalog\Api\Data\ProductInterface::REVIEWS_COUNT
                    ],
                    "review_entity_summary.store_id = {$storeId}",
                    'left'
                );

                $direction = $sortOrderByPopularity->getDirection() == SortOrder::SORT_ASC ? SortOrder::SORT_ASC : SortOrder::SORT_DESC;
                $collection->getSelect()->order("{$sortOrderByPopularity->getField()} {$direction}");
            }



            return null;
        });
    }
    public function getPricesList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        return $this->_getPriceList($searchCriteria, function ($collection, $searchCriteria) {
            return null;
        });
    }
    protected function _getPriceList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria, callable $modifyCollectionCallback = null)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);

        foreach ($this->metadataService->getList($this->searchCriteriaBuilder->create())->getItems() as $metadata) {
            $collection->addAttributeToSelect($metadata->getAttributeCode());
        }
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');

        //Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->removeEmptyFilter($group);
            $this->addFilterGroupToCollection($group, $collection);
        }

        /** @var SortOrder $sortOrder */
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }

        $filters = $modifyCollectionCallback !== null ? $modifyCollectionCallback($collection, $searchCriteria) : null;

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->load();
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        $searchResult->setItems($collection->getItems());
        foreach($collection as $key => $product){
            $this->_addStockInformation($product);
            $this->_addCatalogRule($product);

        }


        if ($filters !== null) {
            $searchResult->setFilters($filters);
        }
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }
    public function getLayeredList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        return $this->_getList($searchCriteria, function ($collection, $searchCriteria) {
            $filters = $this->_getFilters($collection);
            $collection->clear();

            return $filters;
        });
    }

    protected function _getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria, callable $modifyCollectionCallback = null)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);

        foreach ($this->metadataService->getList($this->searchCriteriaBuilder->create())->getItems() as $metadata) {
            $collection->addAttributeToSelect($metadata->getAttributeCode());
        }
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');

        //Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->removeEmptyFilter($group);
            $this->addFilterGroupToCollection($group, $collection);
        }

        /** @var SortOrder $sortOrder */
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }

        $filters = $modifyCollectionCallback !== null ? $modifyCollectionCallback($collection, $searchCriteria) : null;

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->load();

        foreach($collection as $product){
            foreach($product->getProductLinks() as $link){
                $this->linkHelper->addAdditionalLinkData($link);
            }
            $this->_setCustomAttributes($product);
            $this->_addDefaultCategoryInformation($product);
            $this->_addStockInformation($product);
            $this->_addRatingSummary($product);
        }

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        if ($filters !== null) {
            $searchResult->setFilters($filters);
        }
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * @Deprecated
     * Helper function that removes unnecessary filter with blank value
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @return void
     */
    protected function removeEmptyFilter(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup
    ) {
        $filters = [];
        foreach ($filterGroup->getFilters() as $filter) {
            if ($filter->getField()
                && in_array($filter->getField(), $this->filterAttributesToBeValidated)
                && $filter->getValue() == self::EMPTY_FILTER_VALUE
                )
            {
                continue;
            }
            $filters[] = $filter;
        }

        $filterGroup->setFilters($filters);

    }

    /**
     * Adds rating summary to product
     * 
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product
     */
    protected function _addRatingSummary($product)
    {
        $summaryData = $this->summaryFactory
                            ->create()
                            ->setStoreId($this->storeManager->getStore()->getId())
                            ->load($product->getId());
        
        $product
            ->setRatingSummary($summaryData->getRatingSummary())
            ->setReviewsCount($summaryData->getReviewsCount());
        
        return $product;
    }

    /**
     * Adds size chart information to product
     * 
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product
     */
    protected function _addSizeChartInformation($product)
    {
        $sizeChartModel = $this->sizeChartModelFactory->create()
                                                      ->load($product->getSizeChart());

        if ($sizeChartModel->getEntityId()) {
            $this->sizeChart->setEntityId($sizeChartModel->getEntityId());
            $this->sizeChart->setName($sizeChartModel->getName());
            $this->sizeChart->setUrl($sizeChartModel->getValue());

            $product->setSizeChart($this->sizeChart);
        }
        
        return $product;
    }
    
    /**
     * Add default catgory information
     * 
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product
     */
    protected function _addDefaultCategoryInformation($product)
    {
        if (!is_null($product->getDefaultCategory())) {
            $category = $this->categoryRepository->get($product->getDefaultCategory());

            $product->setDefaultCategory($category->getUrlKey());

            $product->setDefaultCategoryName($category->getName());
        }
        return $product;
    }

    /**
     * Add product image
     * 
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product
     */
    protected function _addProductImage($product)
    {
        $image = $product->getMediaGalleryImages()
                             ->getFirstItem();            
            
        $product->setProductImage($image["file"]);
        
        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function get($sku, $editMode = false, $storeId = null, $forceReload = false, $ratingSummary = false)
    {

        $productId = $this->resourceModel->getIdBySku($sku);

        $product = $this->getById($productId, $editMode, $storeId, $forceReload, $ratingSummary, true);
        
        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function getByUrlKey($urlKey, $editMode = false, $storeId = null, $forceReload = false, $ratingSummary = false)
    {
        $product = $this->productFactory->create()->loadByAttribute('url_key', $urlKey);
        if(!$product){
            throw new NoSuchEntityException(__('Requested product doesn\'t exist or wrong url key provided.'));
        }

        $product = $this->getById($product->getId(), $editMode, $storeId, $forceReload, $ratingSummary,true);

        // Add default category information
        //$product = $this->_addDefaultCategoryInformation($product);

        return $product;
    }

    public function getById($productId, $editMode = false, $storeId = null, $forceReload = false, $ratingSummary = false, $catalogRuleInfo = false)
    {
        $cacheKey = $this->getCacheKey([$editMode, $storeId]);
        if (!isset($this->instancesById[$productId][$cacheKey]) || $forceReload) {

            $product = $this->productFactory->create();
            if ($editMode) {
                $product->setData('_edit_mode', true);
            }
            if ($storeId !== null) {
                $product->setData('store_id', $storeId);
            }
            $product->load($productId);
            if (!$product->getId()) {
                throw new NoSuchEntityException(__('Requested product doesn\'t exist'));
            }

            $product = $this->_setCustomAttributes($product);

            // Add rating summary to product
            if ($ratingSummary)
                $product = $this->_addRatingSummary($product);


            $product = $this->_setProductLinkCustomAttributes($product);

            // Add size chart information
            $product = $this->_addSizeChartInformation($product);

            // Add default category information
            $product = $this->_addDefaultCategoryInformation($product);

            // Add product image
            $product = $this->_addProductImage($product);

            // Add catalog rule infomration
            if ($catalogRuleInfo)
                $product = $this->_addCatalogRule($product);

            $this->instancesById[$productId][$cacheKey] = $product;
            $this->instances[$product->getSku()][$cacheKey] = $product;
        }
        return $this->instancesById[$productId][$cacheKey];
    }


    public function getByIdBasic($productId, $editMode = false, $storeId = null, $forceReload = false)
    {
        $cacheKey = $this->getCacheKey([$editMode, $storeId]);
        if (!isset($this->instancesById[$productId][$cacheKey]) || $forceReload) {

            $product = $this->productFactory->create();
            if ($editMode) {
                $product->setData('_edit_mode', true);
            }
            if ($storeId !== null) {
                $product->setData('store_id', $storeId);
            }
            $product->load($productId);
            if (!$product->getId()) {
                throw new NoSuchEntityException(__('Requested product doesn\'t exist'));
            }

            $product = $this->_setCustomAttributes($product);
            // Add default category information
            $product = $this->_addDefaultCategoryInformation($product);

            $this->instancesById[$productId][$cacheKey] = $product;
            $this->instances[$product->getSku()][$cacheKey] = $product;
        }
        return $this->instancesById[$productId][$cacheKey];
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     *
     * @return array
     */
    protected function _getFilters($collection)
    {
        $attributes = $this->filterableAttributes->getList();
        $filters = [];

        foreach ($attributes as $attribute) {
            /** @var FilterInterface $filter */
            $filter = $this->filtersFactory->create();
            foreach ($attribute->getOptions() as $optionItem) {
                if ($optionItem->getValue()) {
                    $option = $this->filtersOptionsFactory->create();
                    $option->setValue($optionItem->getValue());
                    $option->setLabel($optionItem->getLabel());
                    $filter->addOption($option);
                }
            }
            $filter->setLabel($attribute->getFrontendLabel());
            $filter->setAttributeCode($attribute->getAttributeCode());
            $showEmpty = $attribute->getIsFilterable() == \Magento\Catalog\Model\Layer\Filter\Attribute::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS ? false : true;
            $filter->setShowEmpty($showEmpty);
            $filters[$attribute->getAttributeCode()] = $filter;
        }

        foreach ($collection as $product) {
            foreach ($filters as $attributeCode => $filter) {
                $values = explode(',', $product->getDataByKey($attributeCode));
                foreach($values as $value){
                    if(!empty($value)) {
                        $option = $filter->getOptionByValue($value);
                        if(null !== $option) {
                            $option->setProductCount((int)($option->getProductCount()) + 1);
                        }
                    }
                }
            }
        }

        foreach ($filters as $attributeCode => $filter){
            if(!$filter->getShowEmpty()) {
                foreach ($filter->getOptions() as $option) {
                    if (!(bool)$option->getProductCount()) {
                        $filter->removeOption($option->getValue());
                    }
                }
            }
        }

        return $filters;
    }


    protected function _setCustomAttributes($product)
    {
        $customAttributes = $product->getCustomAttributes();
        $tier_price_array = array();
        if ($product->getTypeId() == "configurable") {
            $_children = $product->getTypeInstance()->getUsedProducts($product);
            if (!empty($_children)) {
                foreach ($_children as $child) {
                    $childId = $child->getID();
                    $childproduct = $this->productFactory->create()->load($childId);
                    $tier_price = $childproduct->getTierPrices();
                    if (count($tier_price) > 0) {
                        foreach ($tier_price as $price) {
                            $tier_price_array[$childId][] = $price->getData();
                        }
                    }else{
                        $tier_price_array[$childId] = [];
                    }
                }
            }
        }
        if ($product->getTypeId() == "bundle") {
            $bundle_tier_price_array = $product->getTierPrices();

            //print_r($bundle_tier_price_array);

            if(!empty($bundle_tier_price_array)){
                $selectionCollection = $product->getTypeInstance(true)
                    ->getSelectionsCollection(
                        $product->getTypeInstance(true)->getOptionsIds($product),
                        $product
                    );

                foreach ($selectionCollection as $proselection) {
                    $childEntityId = $proselection->getEntityId();
                    $selectionArray[$childEntityId]['selection_product_quantity'] = $proselection->getSelectionQty();
                    $selectionArray[$childEntityId]['selection_product_price'] = $proselection->getSelectionPriceValue();
                }

                $tier_price_array = [];
                if (count($bundle_tier_price_array) > 0) {
                    foreach ($bundle_tier_price_array as $key => $price) {
                        //print_r($price->getData());
                        $groupPrice = 0;

                        $regularPrice = $product->getPriceInfo()->getPrice('final_price')->getMinimalPrice()->getValue();
                        $groupPrice = $regularPrice * ((100-$price->getExtensionAttributes()->getPercentageValue()) / 100);


                        $tier_price_array[$key]['customer_group_id'] = $price->getData('customer_group_id');
                        $tier_price_array[$key]['qty'] = $price->getData('qty');
                        $tier_price_array[$key]['value'] = $groupPrice;
                    }
                }
            }
        }

        $firstKey = '';
        if(!empty($tier_price_array)){
            $tier_price_array_keys = array_keys($tier_price_array);
            if(!empty($tier_price_array_keys)){
                $firstKey = $tier_price_array_keys[0];
            }

        }

        $custom_tier_prices = ['attribute_label'=> null,'attribute_code'=>'tier_prices','attribute_value_default_key'=>$firstKey,'value'=> $tier_price_array];
        $updateCustomAttributes = [];
        $categoryIdsAdded = false;
        foreach($customAttributes as $customAttribute){
            /** @var \Funimation\Catalog\Model\Data\Attribute $updatedAttribute */
            $updatedAttribute = $this->attributeFactory->create();
            $updatedAttribute->setAttributeCode($customAttribute->getAttributeCode());
            $updatedAttribute->setValue($customAttribute->getValue());

            if ($customAttribute->getAttributeCode() == 'category_ids') {
                $categories = $this->categoryCollectionFactory->create();
                $categories->setStoreId($this->storeManager->getStore()->getId())
                    ->addAttributeToSelect('entity_id')
                    ->addAttributeToSelect('name')
                    ->addAttributeToSelect('url_key')
                    ->addAttributeToSelect('level')
                    ->addAttributeToSelect('parent_id')
                    ->addAttributeToFilter('entity_id', array('in'=>$customAttribute->getValue()));

                $categoryValue = array();
                foreach ($categories as $category) {
                    $categoryValue[] = array(
                        'id'=>$category->getData('entity_id'),
                        'name'=>$category->getData('name'),
                        'url_key'=>$category->getData('url_key'),
                        'level'=>$category->getData('level'),
                        'parent_id'=>$category->getData('parent_id')
                    );
                }
                if ($categoryValue){
                    $updatedAttribute->setValue($categoryValue);
                }
                $categoryIdsAdded = true;
            }

            $label = $this->attributeSource->getAttributeSourceText($customAttribute);
            $updatedAttribute->setAttributeLabel($label);
            $updateCustomAttributes[] = $updatedAttribute;
        }
        
        if(!$categoryIdsAdded){
            $updatedAttribute = $this->attributeFactory->create();
            $updatedAttribute->setAttributeCode('category_ids');
            $updatedAttribute->setValue($product->getCategoryIds());
            $categories = $this->categoryCollectionFactory->create();
                $categories->setStoreId($this->storeManager->getStore()->getId())
                    ->addAttributeToSelect('entity_id')
                    ->addAttributeToSelect('name')
                    ->addAttributeToSelect('url_key')
                    ->addAttributeToSelect('level')
                    ->addAttributeToSelect('parent_id')
                    ->addAttributeToFilter('entity_id', array('in'=>$product->getCategoryIds()));

                $categoryValue = [];
                foreach ($categories as $category) {
                    $categoryValue[] = array(
                        'id'=>$category->getData('entity_id'),
                        'name'=>$category->getData('name'),
                        'url_key'=>$category->getData('url_key'),
                        'level'=>$category->getData('level'),
                        'parent_id'=>$category->getData('parent_id')
                    );
                }
                if ($categoryValue){
                    $updatedAttribute->setValue($categoryValue);
                }
                $label = $this->attributeSource->getAttributeSourceText($updatedAttribute);
                $updatedAttribute->setAttributeLabel($label);
                $updateCustomAttributes[] = $updatedAttribute;
        }
        $updateCustomAttributes[] = $custom_tier_prices;
        $product->setAdditionalAttributes($updateCustomAttributes);

        return $product;
    }

    protected function _addStockInformation($product) {
        //** add stock information */
        $productExtension = $product->getExtensionAttributes();

            // stockItem := \Magento\CatalogInventory\Api\Data\StockItemInterface
        $productExtension->setStockItem($this->stockRegistry->getStockItem($product->getId()));
        $product->setExtensionAttributes($productExtension);
        return $product;
    }

    protected function _addCatalogRule($product) {
        $date = strtotime('now');
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $customerGroupId = $this->customerSession->getCustomerGroupId();
        $productId = $product->getId();

        /*
        $connection = $this->ruleResourceModel->getConnection();
        $select = $connection->select()
            ->from( array("main_table" => $this->ruleResourceModel->getTable('catalogrule')), ['name'=>'main_table.name','description'=>'main_table.description'])
            ->joinInner(array('catalogrule_product'=>$this->ruleResourceModel->getTable('catalogrule_product')) , "main_table.rule_id = catalogrule_product.rule_id", [])

            ->where('catalogrule_product.website_id = ?', $websiteId)
            ->where('catalogrule_product.customer_group_id = ?', $customerGroupId)
            ->where('catalogrule_product.product_id = ?', $productId)
            ->where('catalogrule_product.from_time = 0 or catalogrule_product.from_time < ?', $date)
            ->where('catalogrule_product.to_time = 0 or catalogrule_product.to_time > ?', $date)
            //->distinct(true)
            ->columns('main_table.rule_id')
            ->group('main_table.rule_id')
        ;

        //var_dump($select->__toString());
        $rules = $connection->fetchAll($select);
        */

        $ruleCollection = $this->rule->getCollection()
            ->join(array('catalogrule_product'=>$this->rule->getResource()->getTable('catalogrule_product')) , "main_table.rule_id = catalogrule_product.rule_id", [])
            ->addFieldToFilter('catalogrule_product.website_id', array('eq'=>$websiteId))
            ->addFieldToFilter('catalogrule_product.customer_group_id', array('eq'=>$customerGroupId))
            ->addFieldToFilter('catalogrule_product.product_id', array('eq'=>$productId))
            ->addFieldToFilter('main_table.is_active', array('eq'=>1))
            ->addFieldToFilter('catalogrule_product.from_time', [ ['eq'=>0] , ['lt'=> $date]])
            ->addFieldToFilter('catalogrule_product.to_time', [['eq'=>0], ['gt'=> $date]])
            ;

        $rules=[];
        foreach ($ruleCollection as $rule) {
            $rules[] = $rule;
        }
        
        $product->setCatalogRule($rules);

        return $product;
    }

    /**
     * @param $product
     * @return $product
     */
    protected function _setProductLinkCustomAttributes($product)
    {
        foreach ($product->getProductLinks() as $link) {
            $this->linkHelper->addAdditionalLinkData($link);
        }

        return $product;
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param Collection $collection
     * @return void
     */
    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        Collection $collection
    ) {
        $fields = [];
        $attributes = [];
        $categoryFilter = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $conditionType = $filter->getConditionType() ? $filter->getConditionType() : 'eq';

            if ($filter->getField() == 'category_id') {
                $categoryFilter[$conditionType][] = $filter->getValue();
                continue;
            }

            if ($filter->getField()
                && in_array($filter->getField(), $this->filterAttributesToBeValidated)
            )
            {
                $attributes[] = ['attribute' => $filter->getField(), $conditionType => $filter->getValue(), 'left'];
            } else {

                $attributes[] = ['attribute' => $filter->getField(), $conditionType => $filter->getValue()];
            }


        }

        if ($categoryFilter) {
            $collection->addCategoriesFilter($categoryFilter);
        }

        if ($fields) {
            $collection->addFieldToFilter($fields);
        }

        if ($attributes) {
            $collection->addAttributeToFilter($attributes);
        }
    }

	/**
     * {@inheritdoc}
     */
    public function getPriceAndStatus($sku, $editMode = false, $storeId = null, $forceReload = false, $ratingSummary = false)
    {

        $productId = $this->resourceModel->getIdBySku($sku);

        $product = $this->getByIdPriceAndStatus($productId, $editMode, $storeId, $forceReload, true);

        return $product;
    }

    public function getByIdPriceAndStatus($productId, $editMode = false, $storeId = null, $forceReload = false, $catalogRuleInfo = false)
    {
        $cacheKey = $this->getCacheKey([$editMode, $storeId]);
        if (!isset($this->instancesById[$productId][$cacheKey]) || $forceReload) {
            $product = $this->productFactory->create();
            if ($editMode) {
                $product->setData('_edit_mode', true);
            }
            if ($storeId !== null) {
                $product->setData('store_id', $storeId);
            }
            $product->load($productId);
            if (!$product->getId()) {
                throw new NoSuchEntityException(__('Requested product doesn\'t exist'));
            }
            // Add catalog rule infomration
            if ($catalogRuleInfo)
                $product = $this->_addCatalogRule($product);

            $this->instancesById[$productId][$cacheKey] = $product;
            $this->instances[$product->getSku()][$cacheKey] = $product;
        }
        $result = $this->instancesById[$productId][$cacheKey];
        return $result;
    }

}