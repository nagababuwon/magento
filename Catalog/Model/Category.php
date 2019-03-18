<?php

namespace Magento\Catalog\Model;

/**
 * Catalog category
 *
 * @method Category setAffectedProductIds(array $productIds)
 * @method array getAffectedProductIds()
 * @method Category setMovedCategoryId(array $productIds)
 * @method int getMovedCategoryId()
 * @method Category setAffectedCategoryIds(array $categoryIds)
 * @method array getAffectedCategoryIds()
 * @method Category setUrlKey(string $urlKey)
 * @method Category setUrlPath(string $urlPath)
 * @method Category getSkipDeleteChildren()
 * @method Category setSkipDeleteChildren(boolean $value)
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Category
    extends \Magento\Catalog\Model\Category 
    implements \Funimation\Catalog\Api\Data\CategoryTreeInterface
{
    const KEY_INCLUDE_IN_MENU = 'include_in_menu';
    
    /**
     * {@inheritdoc}
     */
    public function getIncludeInMenu()
    {
        return $this->getData(self::KEY_INCLUDE_IN_MENU);
    }

    /**
     * {@inheritdoc}
     */
    public function setIncludeInMenu($includeInMenu)
    {
        return $this->setData(self::KEY_INCLUDE_IN_MENU, $includedInMenu);
    }
}
