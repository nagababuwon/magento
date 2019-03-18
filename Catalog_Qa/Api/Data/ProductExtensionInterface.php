<?php
/**
 * Class ProductExtensionInterface
 */

namespace Funimation\Catalog\Api\Data;


interface ProductExtensionInterface
    extends \Magento\Catalog\Api\Data\ProductExtensionInterface
{
    /**
     * @return \Funimation\Catalog\Api\Data\ConfigurableOptionInterface[]|null
     */
    public function getConfigurableProductOptions();

    /**
     * @param \Funimation\Catalog\Api\Data\ConfigurableOptionInterface[]
     * $configurableProductOptions
     * @return $this
     */
    public function setConfigurableProductOptions($configurableProductOptions);
}