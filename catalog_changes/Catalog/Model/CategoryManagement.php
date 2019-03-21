<?php

namespace Funimation\Catalog\Model;

class CategoryManagement 
    extends \Magento\Catalog\Model\CategoryManagement
    implements \Funimation\Catalog\Api\CategoryManagementInterface
{
    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface 
     */
    protected $categoryRepository;
    
    /**
     * Constructor
     * 
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Catalog\Model\Category\Tree $categoryTree
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoriesFactory
     */
    public function __construct(
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\Category\Tree $categoryTree,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoriesFactory            
    ) {
        parent::__construct($categoryRepository, $categoryTree, $categoriesFactory);
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Add categories missing information to categories tree
     * 
     * @param \Funimation\Catalog\Api\Data\CategoryTreeInterface[] $categories
     * @return \Funimation\Catalog\Api\Data\CategoryTreeInterface[]
     */
    protected function _addCategoryInfo($categories)
    {
        // Get category data
        $categoryData = $this->categoryRepository->get($categories->getId());
        
        // Set include in menu attribute
        $categories->setIncludeInMenu($categoryData->getIncludeInMenu());
        
        $categories->setMetaTitle($categoryData->getMetaTitle());
        $categories->setMetaKeywords($categoryData->getMetaKeywords());
        $categories->setMetaDescription($categoryData->getMetaDescription());

        // Add info to children
        $children = [];
        foreach ($categories->getChildrenData() as $child) {
            $children[] = $this->_addCategoryInfo($child);
        }
        
        // Set children
        $categories->setChildrenData($children);
        
        return $categories;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getTree($rootCategoryId = null, $depth = null)
    {
        // Get original Magento's category tree data
        $categories = parent::getTree($rootCategoryId, $depth);

        // Add categories missing info
        $fullCategories = $this->_addCategoryInfo($categories);

        return $fullCategories;
    }
}