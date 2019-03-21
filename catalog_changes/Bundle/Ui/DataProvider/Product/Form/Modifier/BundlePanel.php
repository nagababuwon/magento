<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Funimation\Bundle\Ui\DataProvider\Product\Form\Modifier;

use Magento\Ui\Component\Form;

/**
 * Create Ship Bundle Items and Affect Bundle Product Selections fields
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BundlePanel extends \Magento\Bundle\Ui\DataProvider\Product\Form\Modifier\BundlePanel
{
    /**
     * Get selection price value structure
     *
     * @return array
     */
    protected function getSelectionPriceValue()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Form\Field::NAME,
                        'dataType' => Form\Element\DataType\Price::NAME,
                        'formElement' => Form\Element\Input::NAME,
                        'label' => __('Price'),
                        'dataScope' => 'selection_price_value',
                        'value' => '0.00',
                        'imports' => [
                            //to always show the price box
                            //'visible' => '!ns = ${ $.ns }, index = ' . BundlePrice::CODE_PRICE_TYPE . ':checked'
                        ],
                        'sortOrder' => 80,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get selection price type structure
     *
     * @return array
     */
    protected function getSelectionPriceType()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Form\Field::NAME,
                        'dataType' => Form\Element\DataType\Boolean::NAME,
                        'formElement' => Form\Element\Select::NAME,
                        'label' => __('Price Type'),
                        'dataScope' => 'selection_price_type',
                        'value' => '0',
                        'options' => [
                            [
                                'label' => __('Fixed'),
                                'value' => '0'
                            ],
                            [
                                'label' => __('Percent'),
                                'value' => '1'
                            ]
                        ],
                        'imports' => [

                            //to always show the price box
                            //'visible' => '!ns = ${ $.ns }, index = ' . BundlePrice::CODE_PRICE_TYPE . ':checked'
                        ],
                        'sortOrder' => 90,
                    ],
                ],
            ],
        ];
    }



}