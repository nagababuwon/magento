<?php
/**
 * Class ProductLayeredResult
 */

namespace Funimation\Catalog\Model\Data;

use Magento\Framework\Api\SearchResults;

class ProductLayeredResult
    extends SearchResults
    implements \Funimation\Catalog\Api\Data\ProductLayeredResultsInterface
{

    const KEY_FILTERS = 'filters';

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return $this->_get(self::KEY_FILTERS) === null ? [] : $this->_get(self::KEY_FILTERS);
    }

    /**
     * @inheritdoc
     */
    public function setFilters(array $filters)
    {
        return $this->setData(self::KEY_FILTERS, $filters);
    }
}