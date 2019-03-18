<?php
/**
 * Class ProductLinkManagementInterface
 */

namespace Funimation\Catalog\Api;


interface ProductLinkManagementInterface
{
    /**
     * Provide the list of links for a specific product
     *
     * @param string $sku
     * @param string $type
     * @return \Funimation\Catalog\Api\Data\ProductLinkInterface[]
     */
    public function getLinkedItemsByType($sku, $type);
}