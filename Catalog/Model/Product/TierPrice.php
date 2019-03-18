<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Funimation\Catalog\Model\Product;

/**
 * @codeCoverageIgnore
 */
class TierPrice extends \Magento\Framework\Model\AbstractExtensibleModel implements
    \Funimation\Catalog\Api\Data\ProductTierPriceInterface
{
    /**
     * Retrieve tier qty
     *
     * @return float
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * Retrieve price value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /**
     * Set tier qty
     *
     * @param float $qty
     * @return $this
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * Set price value
     *
     * @param float $value
     * @return $this
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * @inheritdoc
     */
    public function getPercentageValue()
    {
        return $this->getData(self::PERCENTAGE_VALUE);
    }

    /**
     * @inheritdoc
     */
    public function setPercentageValue($value)
    {
        return $this->setData(self::PERCENTAGE_VALUE, $value);
    }
    /**
     * @inheritdoc
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }
    /**
     * @inheritdoc
     */
    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * Retrieve customer group id
     *
     * @return int
     */
    public function getCustomerGroupId()
    {
        return $this->getData(self::CUSTOMER_GROUP_ID);
    }

    /**
     * Set customer group id
     *
     * @param int $customerGroupId
     * @return $this
     */
    public function setCustomerGroupId($customerGroupId)
    {
        return $this->setData(self::CUSTOMER_GROUP_ID, $customerGroupId);
    }

    /**
     * {@inheritdoc}
     *
     * @return \Funimation\Catalog\Api\Data\ProductTierPriceExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     *
     * @param \Funimation\Catalog\Api\Data\ProductTierPriceExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Funimation\Catalog\Api\Data\ProductTierPriceExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}