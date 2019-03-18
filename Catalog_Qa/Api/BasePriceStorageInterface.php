<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Funimation\Catalog\Api;

/**
 * Base prices storage.
 * @api
 */
interface BasePriceStorageInterface
{
    /**
     * Return product prices.
     *
     * @param string[] $skus
     * @return \Funimation\Catalog\Api\Data\BasePriceInterface[]
     */
    public function get(array $skus);

    /**
     * Add or update product prices.
     *
     * @param \Funimation\Catalog\Api\Data\BasePriceInterface[] $prices
     * @return bool Will returned True if updated.
     */
    public function update(array $prices);
}