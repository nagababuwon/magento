<?php
namespace Funimation\Catalog\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
 
    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if ($context->getVersion()
            && version_compare($context->getVersion(), '1.1.0') < 0
        ) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'default_category',
                [
                    'type' => 'int',
                    'label' => 'Default Category',
                    'input' => 'select',
                    'backend' => '',
                    'frontend' => '',
                    'class' => '',
                    'source' => 'Funimation\Catalog\Model\Category\Source\Options',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => true,
                    'unique' => false,
                    'apply_to' => 'simple,configurable',
                    'used_in_product_listing' => false
                ]
            );
            $eavSetup->addAttributeToSet(\Magento\Catalog\Model\Product::ENTITY, 'Default', 'Product Details', 'default_category');     
        }

        // This is needed when *Staging modules are enabled. If not, then product edition not work
        if ($context->getVersion()
            && version_compare($context->getVersion(), '1.2.0') < 0
        ) {
            $setup->getConnection()->update(
                'catalog_product_entity',
                ['created_in' => 1],
                'created_in = 0'
            );
        }

        if ($context->getVersion()
            && version_compare($context->getVersion(), '1.3.0') < 0
        ) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'is_sale_item',
                [
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Is Sale Item',
                    'input' => 'boolean',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => true,
                    'filterable' => true,
                    'comparable' => true,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => 'simple,configurable',
                    'visible_on_front' => true
                ]
            );

            $eavSetup->addAttributeToSet(\Magento\Catalog\Model\Product::ENTITY, 'Default', 'Product Details', 'is_sale_item');
        }
        if ($context->getVersion()
            && version_compare($context->getVersion(), '1.3.1') < 0
        ) {
            $connection = $setup->getConnection();

            $connection->addColumn(

                $setup->getTable('catalog_product_entity_tier_price'),

                'percentage_value',

                [

                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,

                    'nullable' => false,

                    'default' => NULL,

                    'length' => '5,2',

                    'comment' => 'Percentage value',

                    'after' => 'value'

                ]

            );
        }

        $setup->endSetup();
    }
}
