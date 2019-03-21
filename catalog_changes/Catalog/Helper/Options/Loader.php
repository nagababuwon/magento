<?php

/**
 * Class Loader
 */

namespace Funimation\Catalog\Helper\Options;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\ConfigurableProduct\Api\Data\OptionInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\ConfigurableProduct\Api\Data\OptionValueInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;

class Loader
    extends \Magento\ConfigurableProduct\Helper\Product\Options\Loader
{
    /**
     * @var OptionValueInterfaceFactory
     */
    protected $optionValueFactory;

    /**
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * ReadHandler constructor
     *
     * @param OptionValueInterfaceFactory $optionValueFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        OptionValueInterfaceFactory $optionValueFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        $this->optionValueFactory = $optionValueFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * @param ProductInterface $product
     * @return OptionInterface[]
     */
    public function load(ProductInterface $product)
    {
        $options = [];
        /** @var Configurable $typeInstance */
        $typeInstance = $product->getTypeInstance();
        $attributeCollection = $typeInstance->getConfigurableAttributeCollection($product);
        $this->extensionAttributesJoinProcessor->process($attributeCollection);
        foreach ($attributeCollection as $attribute) {
            $values = [];
            $attributeOptions = $attribute->getOptions();
            if (is_array($attributeOptions)) {
                foreach ($attributeOptions as $option) {
                    /** @var \Magento\ConfigurableProduct\Api\Data\OptionValueInterface $value */
                    $value = $this->optionValueFactory->create();
                    $value->setValueIndex($option['value_index']);
                    $value->setLabel($option['label']);
                    $values[] = $value;
                }
            }
            $attribute->setValues($values);
            $options[] = $attribute;
        }

        return $options;
    }
}