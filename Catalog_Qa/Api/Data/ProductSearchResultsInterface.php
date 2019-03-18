<?php

namespace Funimation\Catalog\Api\Data;


interface ProductSearchResultsInterface
    extends \Magento\Catalog\Api\Data\ProductSearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Funimation\Catalog\Api\Data\ProductInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Funimation\Catalog\Api\Data\ProductInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}