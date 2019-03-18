<?php
/**
 * Class ConfigurableOptionValue
 */

namespace Funimation\Catalog\Model\Data;

use Funimation\Catalog\Api\Data\ConfigurableOptionValueInterface;

class ConfigurableOptionValue
    extends \Magento\ConfigurableProduct\Model\Product\Type\Configurable\OptionValue
    implements ConfigurableOptionValueInterface
{
    const KEY_VALUE_LABEL = 'label';


    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getData(self::KEY_VALUE_LABEL);
    }

    /**
     * @inheritdoc
     */
    public function setLabel($valueLabel)
    {
        return $this->setData(self::KEY_VALUE_LABEL, $valueLabel);
    }
}