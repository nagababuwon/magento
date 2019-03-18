<?php

namespace Funimation\Catalog\Model\ResourceModel\Product;

class Collection
    extends \Magento\Catalog\Model\ResourceModel\Product\Collection
{
    /**
     * Add attribute to filter
     *
     * @param \Magento\Eav\Model\Entity\Attribute\AbstractAttribute|string $attribute
     * @param array $condition
     * @param string $joinType
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner')
    {
        if ($this->isEnabledFlat()) {
            if ($attribute instanceof \Magento\Eav\Model\Entity\Attribute\AbstractAttribute) {
                $attribute = $attribute->getAttributeCode();
            }

            if (is_array($attribute)) {
                $sqlArr = [];
                foreach ($attribute as $condition) {
                    $sqlArr[] = $this->_getAttributeConditionSql($condition['attribute'], $condition, $joinType);
                }
                $conditionSql = '(' . join(') OR (', $sqlArr) . ')';
                $this->getSelect()->where($conditionSql);
                return $this;
            }

            if (!isset($this->_selectAttributes[$attribute])) {
                $this->addAttributeToSelect($attribute);
            }

            if (isset($this->_selectAttributes[$attribute])) {
                $this->getSelect()->where($this->_getConditionSql('e.' . $attribute, $condition));
            }

            return $this;
        }

        $this->_allIdsCache = null;

        if (is_string($attribute) && $attribute == 'is_saleable') {
            $columns = $this->getSelect()->getPart(\Magento\Framework\DB\Select::COLUMNS);
            foreach ($columns as $columnEntry) {
                list($correlationName, $column, $alias) = $columnEntry;
                if ($alias == 'is_saleable') {
                    if ($column instanceof \Zend_Db_Expr) {
                        $field = $column;
                    } else {
                        $connection = $this->getSelect()->getConnection();
                        if (empty($correlationName)) {
                            $field = $connection->quoteColumnAs($column, $alias, true);
                        } else {
                            $field = $connection->quoteColumnAs([$correlationName, $column], $alias, true);
                        }
                    }
                    $this->getSelect()->where("{$field} = ?", $condition);
                    break;
                }
            }

            return $this;
        }
        elseif  (is_array($attribute)) {
            $sqlArr = [];
            foreach ($attribute as $condition) {
                if (isset($condition[0]))

                    $sqlArr[] = $this->_getAttributeConditionSql($condition['attribute'], $condition, $condition[0]);
                else

                    $sqlArr[] = $this->_getAttributeConditionSql($condition['attribute'], $condition, $joinType);
            }
            $conditionSql = '(' . implode(') OR (', $sqlArr) . ')';
            $this->getSelect()->where($conditionSql, null, \Magento\Framework\DB\Select::TYPE_CONDITION);

        } else {
            return parent::addAttributeToFilter($attribute, $condition, $joinType);
        }
    }

    /**
     * Add tier price data to loaded items
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function addTierPriceData()
    {
        if ($this->getFlag('tier_price_added')) {
            return $this;
        }
        $linkField = $this->getConnection()->getAutoIncrementField($this->getTable('catalog_product_entity'));
        $tierPrices = [];
        $productIds = [];
        foreach ($this->getItems() as $item) {
            $productIds[] = $item->getData($linkField);
            $tierPrices[$item->getData($linkField)] = [];
        }
        if (!$productIds) {
            return $this;
        }

        /** @var $attribute \Magento\Catalog\Model\ResourceModel\Eav\Attribute */
        $attribute = $this->getAttribute('tier_price');
        if ($attribute->isScopeGlobal()) {
            $websiteId = 0;
        } else {
            if ($this->getStoreId()) {
                $websiteId = $this->_storeManager->getStore($this->getStoreId())->getWebsiteId();
            }
        }
        $connection = $this->getConnection();
        $columns = [
            'price_id' => 'value_id',
            'website_id' => 'website_id',
            'all_groups' => 'all_groups',
            'cust_group' => 'customer_group_id',
            'price_qty' => 'qty',
            'price' => 'value',
            'product_id' => $linkField,
        ];
        $select = $connection->select()->from(
            $this->getTable('catalog_product_entity_tier_price'),
            $columns
        )->where(
            $linkField .' IN(?)',
            $productIds
        )->order(
            [$linkField, 'qty']
        );

        if ($websiteId == '0') {
            $select->where('website_id = ?', $websiteId);
        } else {
            $select->where('website_id IN(?)', ['0', $websiteId]);
        }

        foreach ($connection->fetchAll($select) as $row) {
            $tierPrices[$row['product_id']][] = [
                'website_id' => $row['website_id'],
                'cust_group' => $row['all_groups'] ? $this->_groupManagement->getAllCustomersGroup()->getId() : $row['cust_group'],
                'price_qty' => $row['price_qty'],
                'price' => $row['price'],
                'website_price' => $row['price'],
            ];
        }

        /* @var $backend \Magento\Catalog\Model\Product\Attribute\Backend\Tierprice */
        $backend = $attribute->getBackend();

        foreach ($this->getItems() as $item) {
            $data = $tierPrices[$item->getData($linkField)];
            if (!empty($data) && $websiteId) {
                $data = $backend->preparePriceData($data, $item->getTypeId(), $websiteId);
            }
            $item->setData('tier_price', $data);
        }

        $this->setFlag('tier_price_added', true);
        return $this;
    }
}