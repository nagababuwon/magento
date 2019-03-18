<?php

namespace Funimation\Catalog\Api\Data;


/**
 * @api
 */
interface ProductSearchPriceAndStatusInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const SKU = 'sku';

    const NAME = 'name';

    const PRICE = 'price';

    const WEIGHT = 'weight';

    const STATUS = 'status';

    const VISIBILITY = 'visibility';

    const ATTRIBUTE_SET_ID = 'attribute_set_id';

    const TYPE_ID = 'type_id';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const FINAL_PRICE = 'final_price';

    /**#@-*/

    /**
     * Product id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set product id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Product sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Set product sku
     *
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * Product name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set product name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Product attribute set id
     *
     * @return int|null
     */
    public function getAttributeSetId();

    /**
     * Set product attribute set id
     *
     * @param int $attributeSetId
     * @return $this
     */
    public function setAttributeSetId($attributeSetId);

    /**
     * Product price
     *
     * @return float|null
     */
    public function getPrice();

    /**
     * Set product price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * Product status
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Set product status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Product visibility
     *
     * @return int|null
     */
    public function getVisibility();

    /**
     * Set product visibility
     *
     * @param int $visibility
     * @return $this
     */
    public function setVisibility($visibility);

    /**
     * Product type id
     *
     * @return string|null
     */
    public function getTypeId();

    /**
     * Set product type id
     *
     * @param string $typeId
     * @return $this
     */
    public function setTypeId($typeId);

    /**
     * Product created date
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set product created date
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Product updated date
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set product updated date
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Product weight
     *
     * @return float|null
     */
    public function getWeight();

    /**
     * Set product weight
     *
     * @param float $weight
     * @return $this
     */
    public function setWeight($weight);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magento\Catalog\Api\Data\ProductExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Magento\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Magento\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes);



    /**
     * Get list of product options
     *
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface[]|null
     */
    public function getOptions();

    /**
     * Set list of product options
     *
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface[] $options
     * @return $this
     */
    public function setOptions(array $options = null);

    /**
     * Get media gallery entries
     *
     * @return \Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface[]|null
     */
    public function getMediaGalleryEntries();

    /**
     * Set media gallery entries
     *
     * @param \Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface[] $mediaGalleryEntries
     * @return $this
     */
    public function setMediaGalleryEntries(array $mediaGalleryEntries = null);

    /**
     * Gets list of product tier prices
     *
     * @return \Magento\Catalog\Api\Data\ProductTierPriceInterface[]|null
     */
    public function getTierPrices();

    /**
     * Sets list of product tier prices
     *
     * @param \Magento\Catalog\Api\Data\ProductTierPriceInterface[] $tierPrices
     * @return $this
     */
    public function setTierPrices(array $tierPrices = null);

    /**
     * Product final price
     *
     * @return float|null
     */
    public function getFinalPrice();

    /**
     * Set product final priceF
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

   

}
