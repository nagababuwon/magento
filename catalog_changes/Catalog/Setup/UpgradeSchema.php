<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Funimation\Catalog\Setup;

use Magento\Catalog\Model\Product\Attribute\Backend\Media\ImageEntryConverter;
use Magento\Catalog\Model\Product\Exception;
use Magento\Catalog\Model\ResourceModel\Product\Gallery;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Upgrade the Catalog module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.6.0', '<')) {

            $tables = [
                'catalog_product_index_price_cfg_opt_agr_idx',
                'catalog_product_index_price_cfg_opt_agr_tmp',
                'catalog_product_index_price_cfg_opt_idx',
                'catalog_product_index_price_cfg_opt_tmp',
                'catalog_product_index_price_final_idx',
                'catalog_product_index_price_final_tmp',
                'catalog_product_index_price_idx',
                'catalog_product_index_price_opt_agr_idx',
                'catalog_product_index_price_opt_agr_tmp',
                'catalog_product_index_price_opt_idx',
                'catalog_product_index_price_opt_tmp',
                'catalog_product_index_price_tmp',
            ];
            foreach ($tables as $table) {
                $setup->getConnection()->modifyColumn(
                    $setup->getTable($table),
                    'customer_group_id',
                    [
                        'type' => Table::TYPE_INTEGER,
                        'nullable' => false,
                        'unsigned' => true,
                        'default' => '0',
                        'comment' => 'Customer Group ID',
                    ]
                );
            }

            //remove fk from price index table
            $setup->getConnection()->dropForeignKey(
                $setup->getTable('catalog_product_index_price'),
                $setup->getFkName(
                    'catalog_product_index_price',
                    'entity_id',
                    'catalog_product_entity',
                    'entity_id'
                )
            );
            $setup->getConnection()->dropForeignKey(
                $setup->getTable('catalog_product_index_price'),
                $setup->getFkName(
                    'catalog_product_index_price',
                    'website_id',
                    'store_website',
                    'website_id'
                )
            );
            $setup->getConnection()->dropForeignKey(
                $setup->getTable('catalog_product_index_price'),
                $setup->getFkName(
                    'catalog_product_index_price',
                    'customer_group_id',
                    'customer_group',
                    'customer_group_id'
                )
            );

            $this->removeIndexFromPriceIndexTable($setup);
        }


        $setup->endSetup();
    }

    /**
     * Table "catalog_product_index_price_tmp" used as template of "catalog_product_index_price" table
     * for create temporary tables during indexation. Indexes are removed from performance perspective
     * @param SchemaSetupInterface $setup
     */
    private function removeIndexFromPriceIndexTable(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->dropIndex(
            $setup->getTable('catalog_product_index_price_tmp'),
            $setup->getIdxName('catalog_product_index_price_tmp', ['customer_group_id'])
        );
        $setup->getConnection()->dropIndex(
            $setup->getTable('catalog_product_index_price_tmp'),
            $setup->getIdxName('catalog_product_index_price_tmp', ['website_id'])
        );
        $setup->getConnection()->dropIndex(
            $setup->getTable('catalog_product_index_price_tmp'),
            $setup->getIdxName('catalog_product_index_price_tmp', ['min_price'])
        );
    }
}
