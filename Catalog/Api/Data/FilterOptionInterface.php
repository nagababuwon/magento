<?php
/**
 * Class FilterOptionInterface
 */

namespace Funimation\Catalog\Api\Data;


interface FilterOptionInterface
{
    /**
     * @param int $value
     * @return $this
     */
    public function setValue($value);

    /**
     * @return int
     */
    public function getValue();

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param int $productCount
     * @return $this
     */
    public function setProductCount($productCount);

    /**
     * @return int
     */
    public function getProductCount();
}