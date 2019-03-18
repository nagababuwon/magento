<?php

namespace Funimation\Catalog\Api;

/**
 * @api
 */
interface CategoryManagementInterface
    extends \Magento\Catalog\Api\CategoryManagementInterface
{
    /**
     * Retrieve list of categories
     *
     * @param int $rootCategoryId
     * @param int $depth
     * @throws \Magento\Framework\Exception\NoSuchEntityException If ID is not found
     * @return \Funimation\Catalog\Api\Data\CategoryTreeInterface containing Tree objects
     */
    public function getTree($rootCategoryId = null, $depth = null);
}