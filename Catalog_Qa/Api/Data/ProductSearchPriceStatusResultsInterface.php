<?php

namespace Funimation\Catalog\Api\Data;


interface ProductSearchPriceStatusResultsInterface
    extends \Magento\Catalog\Api\Data\ProductSearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Funimation\Catalog\Api\Data\ProductSearchPriceAndStatusInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Funimation\Catalog\Api\Data\ProductSearchPriceAndStatusInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}