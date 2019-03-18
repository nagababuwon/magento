<?php
/**
 * Class ConfigurableOptionValueInterface
 */

namespace Funimation\Catalog\Api\Data;


interface ConfigurableOptionValueInterface
    extends \Magento\ConfigurableProduct\Api\Data\OptionValueInterface
{
    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $valueLabel
     * @return $this
     */
    public function setLabel($valueLabel);
}