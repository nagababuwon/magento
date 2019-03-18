<?php
 
namespace Funimation\Catalog\Model\Category\Source;
 
class Options 
    extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Collection 
     */
    protected $categoryCollection;
    
    /**
     * Constructor
     * 
     * @param \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection
    ){
        $this->categoryCollection = $categoryCollection;
    }
    
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        // No size chart option
        $option = [];
        
        
        foreach ($this->categoryCollection as $category) {
            $category->load($category->getId());
            
            $option = [];
            $option['label'] = $category->getName();
            $option['value'] = $category->getId();
            $this->_options[$category->getName()] = $option;
        }
        
        ksort($this->_options);
        
        $option['label'] = 'Select default category...';
        $option['value'] = '';
        $this->_options[0] = $option;
        
        return $this->_options;
    }
 
    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}