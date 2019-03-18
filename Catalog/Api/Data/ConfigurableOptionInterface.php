<?php
/**
 * Class ConfigurableOptionInterface
 */

namespace Funimation\Catalog\Api\Data;


interface ConfigurableOptionInterface
    extends \Magento\ConfigurableProduct\Api\Data\OptionInterface
{
    /**
     * @return \Funimation\Catalog\Api\Data\ConfigurableOptionValueInterface[]|null
     */
    public function getValues();

    /**
     * @param \Funimation\Catalog\Api\Data\ConfigurableOptionValueInterface[] $values
     * @return $this
     */
    public function setValues(array $values = null);
}