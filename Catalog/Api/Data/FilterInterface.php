<?php
/**
 * Class FilterInterface
 */

namespace Funimation\Catalog\Api\Data;


interface FilterInterface
{
    /**
     * @param string $attributeCode
     * @return $this
     */
    public function setAttributeCode($attributeCode);

    /**
     * @return string
     */
    public function getAttributeCode();

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
     * @param \Funimation\Catalog\Api\Data\FilterOptionInterface[] $options
     * @return $this
     */
    public function setOptions(array $options);

    /**
     * @return \Funimation\Catalog\Api\Data\FilterOptionInterface[]
     */
    public function getOptions();

    /**
     * @param \Funimation\Catalog\Api\Data\FilterOptionInterface $option
     * @return $this
     */
    public function addOption($option);

    /**
     * @param string $value
     * @return $this
     */
    public function removeOption($value);

    /**
     * @param string $value
     * @return \Funimation\Catalog\Api\Data\FilterOptionInterface|null
     */
    public function getOptionByValue($value);
}