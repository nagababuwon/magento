<?php

namespace Funimation\Catalog\Api;

/**
 * Class ProductRepositoryInterface
 */
interface ProductRepositoryInterface
{
    /**
     * Get product list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Funimation\Catalog\Api\Data\ProductLayeredResultsInterface
     */
    public function getLayeredList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Get product list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Funimation\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Get product list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Funimation\Catalog\Api\Data\ProductSearchPriceStatusResultsInterface
     */
    public function getPricesList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
    /**
     * Get info about product by product SKU
     *
     * @param string $sku
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @param bool $ratingSummary Flag to add rating summary to product
     * @param array $additionalDataConfig Config o add additional data to product
     * @return \Funimation\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($sku, $editMode = false, $storeId = null, $forceReload = false, $ratingSummary = false);

    /**
     * Get info about product by product Id
     *
     * @param string $sku
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @param bool $ratingSummary Flag to add rating summary to product
     * @param array $additionalDataConfig Config o add additional data to product
     * @return \Funimation\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($sku, $editMode = false, $storeId = null, $forceReload = false, $ratingSummary = false);

    /**
     * Get info about product by product Id with minimum product data
     *
     * @param string $sku
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @param array $additionalDataConfig Config o add additional data to product
     * @return \Funimation\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByIdBasic($sku, $editMode = false, $storeId = null, $forceReload = false);

    /**
     * Get info about product by product SKU
     *
     * @param string $urlKey
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @param bool $ratingSummary Flag to add rating summary to product
     * @param array $additionalDataConfig Config o add additional data to product
     * @return \Funimation\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByUrlKey($urlKey, $editMode = false, $storeId = null, $forceReload = false, $ratingSummary = false);
	
	/**
     * Get info about product by product SKU
     *
     * @param string $sku
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @param bool $ratingSummary Flag to add rating summary to product
     * @param array $additionalDataConfig Config o add additional data to product
     * @return \Funimation\Catalog\Api\Data\ProductPriceAndStatusInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPriceAndStatus($sku, $editMode = false, $storeId = null, $forceReload = false, $ratingSummary = false);
}