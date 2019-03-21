<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Funimation\Catalog\Model;

class Product extends \Magento\Catalog\Model\Product
{

    /**
     * Get product price model
     *
     * @return \Funimation\Catalog\Model\Product\Type\Price
     */
    public function getPriceModel()
    {
        return $this->_catalogProductType->priceFactory($this->getTypeId());
    }

    /**
     * Gets list of product tier prices
     *
     * @return \Funimation\Catalog\Api\Data\ProductTierPriceInterface[]|null
     */
    public function getTierPrices()
    {
        return $this->getPriceModel()->getTierPrices($this);
    }

    /**
     * Sets list of product tier prices
     *
     * @param \Funimation\Catalog\Api\Data\ProductTierPriceInterface[] $tierPrices
     * @return $this
     */
    public function setTierPrices(array $tierPrices = null)
    {
        $this->getPriceModel()->setTierPrices($this, $tierPrices);
        return $this;
    }


}
