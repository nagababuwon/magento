<?php

namespace Funimation\Catalog\Api\Data;


/**
 * Interface ProductInterface
 */
interface ProductInterface
    extends \Magento\Catalog\Api\Data\ProductInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const RATING_SUMMARY = 'rating_summary';
    const REVIEWS_COUNT = 'reviews_count';
    const SIZE_CHART = 'size_chart';
    const DEFAULT_CATEGORY = 'default_category';
    const PRODUCT_IMAGE = 'product_image';
    const FINAL_PRICE = 'final_price';
    
    /**
     * Retrieve additional attributes.
     *
     * @return \Funimation\Catalog\Api\Data\AttributeInterface[]|null
     */
    public function getAdditionalAttributes();

    /**
     * Sets additional attributes
     *
     * @param \Funimation\Catalog\Api\Data\AttributeInterface[] $additionalAttributes
     * @return $this
     */
    public function setAdditionalAttributes($additionalAttributes);

    /**
     * Set product rating summary.
     *
     * @param int $value
     * @return $this
     */
    public function setRatingSummary($value);

    /**
     * Product rating summary.
     *
     * @return int
     */
    public function getRatingSummary();

    /**
     * Set product size chart
     *
     * @param \Funimation\SizeChart\Api\Data\SizeChartInterface $value
     * @return $this
     */
    public function setSizeChart($value);

    /**
     * Product Size Chart
     *
     * @return \Funimation\SizeChart\Api\Data\SizeChartInterface
     */
    public function getSizeChart();    
    
    /**
     * Set product reviews count.
     *
     * @param int $value
     * @return $this
     */
    public function setReviewsCount($value);

    /**
     * Product reviews count.
     *
     * @return int
     */
    public function getReviewsCount();

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Funimation\Catalog\Api\Data\ProductExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Funimation\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Magento\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes);

    /**
     * Get product links info
     *
     * @return \Funimation\Catalog\Api\Data\ProductLinkInterface[]|null
     */
    public function getProductLinks();
    
    /**
     * Set product default category
     *
     * @param string $value
     * @return $this
     */
    public function setDefaultCategory($value);

    /**
     * Product default category
     *
     * @return string
     */
    public function getDefaultCategory();

    /**
     * Set product default category name
     *
     * @param string $name
     * @return $this
     */
    public function setDefaultCategoryName($name);

    /**
     * Product default category name
     *
     * @return string
     */
    public function getDefaultCategoryName();
    
    /**
     * Get product image
     *
     * @return string
     */
    public function getProductImage();

    /**
     * Set product image
     * 
     * @param type $productImage
     * @return string
     */
    public function setProductImage($productImage);

    /**
     * Product final price
     *
     * @return float|null
     */
    public function getFinalPrice();

    /**
     * Set product final price
     *
     * @param float $price
     * @return $this
     */
    public function setFinalPrice($finalPrice);

    /**
     * Set Catalog Rule
     *
     * @return \Magento\CatalogRule\Api\Data\RuleInterface[]|null
     */
    public function getCatalogRule();

    /**
     * Set product final price
     *
     * @param \Magento\CatalogRule\Api\Data\RuleInterface $catalogRule
     * @return $this
     */
    public function setCatalogRule($catalogRule);

    /**
     * Gets list of product tier prices
     *
     * @return \Funimation\Catalog\Api\Data\ProductTierPriceInterface[]|null
     */
    public function getTierPrices();

    /**
     * Sets list of product tier prices
     *
     * @param \Funimation\Catalog\Api\Data\ProductTierPriceInterface[] $tierPrices
     * @return $this
     */
    public function setTierPrices(array $tierPrices = null);
}
