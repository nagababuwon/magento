<?php
/**
 * Class ProductLayeredResultsInterface
 */

namespace Funimation\Catalog\Api\Data;

interface ProductLayeredResultsInterface
{
    /**
     * Get filters list.
     *
     * @return \Funimation\Catalog\Api\Data\FilterInterface[]
     */
    public function getFilters();

    /**
     * Set filters list.
     *
     * @param \Funimation\Catalog\Api\Data\FilterInterface[] $filters
     *
     * @return $this
     */
    public function setFilters(array $filters);


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
