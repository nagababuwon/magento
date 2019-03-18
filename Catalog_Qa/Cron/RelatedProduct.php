<?php

namespace Funimation\Catalog\Cron;

class RelatedProduct
{
    const RELATED_PRODUCT_ID = 1;
    
    /**
     * @var \Funimation\Catalog\Model\ResourceModel\LinkedProduct\CollectionFactory 
     */
    protected $linkedProductCollectionFactory;

    /**
     * @var []
     */    
    protected $relatedProductsOnDb;
    
    /**
     * @var \Funimation\Catalog\Model\LinkedProductFactory 
     */
    protected $linkedProductFactory;
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    
    public function __construct(
        \Funimation\Catalog\Model\ResourceModel\LinkedProduct\CollectionFactory $linkedProductCollectionFactory,
        \Funimation\Catalog\Model\LinkedProductFactory $linkedProductFactory,
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ){
        $this->linkedProductCollectionFactory = $linkedProductCollectionFactory;
        $this->linkedProductFactory = $linkedProductFactory;
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * Get related products from DB
     * 
     * @return []
     */
    protected function _getRelatedProductsOnDatabase()
    {
        
        
        // Init related products on DB array
        $this->relatedProductsOnDb = [];
        
        // Get related products from table
        $relatedProducts = $this->linkedProductCollectionFactory->create()
                                                                ->addFilter('link_type_id', self::RELATED_PRODUCT_ID);
        
        // Generate related products array
        foreach ($relatedProducts as $link) {
            $this->relatedProductsOnDb[$link->getProductId()][] = $link->getLinkedProductId(); 
        }
        
        return $this->relatedProductsOnDb;
    }

    /**
     * Find more relations between products
     * 
     * @param [] $relations
     * @return []
     */
    protected function _getFindMoreRelations($relations)
    {
        /**
         * This function is called by $this->_getAllRelations()
         * 
         * Each time it is called, all the DIRECT relations are merged
         * This function is called since there are no differences between
         * the original array and the new one created
         * 
         */
        $newRelations = $relations;
        
        if (count($relations) > 0) {
            foreach ($relations as $productId => $related) {
                foreach ($related as $relatedProductId) {
                    // First array is the productId index array
                    $a1 = $newRelations[$productId];
                    
                    // Second array is the productId index value, if it exists
                    $a2 = isset($newRelations[$relatedProductId]) ? $newRelations[$relatedProductId] : [];
                    
                    // Third array is the same productId. This is added because if it is not, the relations
                    // with the first productId are missed
                    $a3 = [$productId];

                    // Find arrays merge
                    $mix = array_unique(array_merge($a1, $a2, $a3));

                    // Assign them to both, productId and productId value arrays
                    $newRelations[$productId] = $mix;
                    $newRelations[$relatedProductId] = $mix;
                }
            }
        }
        
        return $newRelations;
    }
    
    /**
     * Check if original relations array and array with new relations are different
     * 
     * @param [] $relations
     * @param [] $newRelations
     * @return boolean
     */
    protected function _checkIsDifferent($relations, $newRelations)
    {
        foreach ($newRelations as $key => $value) {
            // If index is not set, then is different still
            if (!isset($relations[$key])) {
                return true;
            }
            
            // If values are different, then is different still
            if (count(array_diff($relations[$key], $newRelations[$key])) > 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get all the relations needed
     * 
     * @param [] $relations
     * @return []
     */
    protected function _getAllRelations($relations)
    {
        // Loops thru relations. When the array in the response is the same that
        // was sended, then finish
        $diff = true;
        while ($diff) {
            // Find more relations
            $newRelations = $this->_getFindMoreRelations($relations);
            
            // Check differences between origin and new relations array
            $diff = $this->_checkIsDifferent($relations, $newRelations);
            
            // Assign new relations to relations
            $relations = $newRelations;
        }

        return $relations;
    }
    
    /**
     * Get relations to create on DB
     * 
     * @param [] $dbRelations
     * @param [] $allRelations
     * @return []
     */
    protected function _getRelationsToCreateOnDb($dbRelations, $allRelations)
    {
        // Init new relations array
        $newRelations = [];
        
        // Loop thru all relations
        foreach ($allRelations as $productId => $relations) {
            
            if (!isset($dbRelations[$productId])) {
                // If productId index does not exists in DB array
                // Then add to new relations array with all relations founded
                $newRelations[$productId] = $allRelations[$productId];
            } else {
                // If productId index exists in DB
                // Then get the difference
                $new = array_diff($allRelations[$productId], $dbRelations[$productId]);
                
                if (count($new) > 0) {
                    // If there are differences, assign them to he new relations array
                    $newRelations[$productId] = $new;
                }
            }
            
            // Delete from array the same productId as relation
            // This is added in the found all relations process
            if (($key = array_search($productId, $newRelations[$productId])) !== false) {
                // Unset the position with the same productId as value
                unset($newRelations[$productId][$key]);
                
                // If the array now is empty, unset it
                if (count($newRelations[$productId]) == 0) {
                    unset($newRelations[$productId]);
                }
            }
        }
        
        return $newRelations;
    }
    
    /**
     * Create collection to save in DB
     * 
     * @param [] $newDbRelations
     * @return \Funimation\Catalog\Model\ResourceModel\LinkedProduct\Collection
     */
    protected function _createNewCollection($newDbRelations)
    {
        // Get empty collection
        // The link_id == 0 is to enforce collection is empty
        $collection = $this->linkedProductCollectionFactory->create()
                                                           ->addFilter('link_id', 0);
        
        // Loop thru new relations, create items and add to collection
        foreach ($newDbRelations as $productId => $relations) {
            foreach ($relations as $relatedProductId) {
                // Create new item
                $elem = $this->linkedProductFactory->create();
                $elem->setProductId($productId);
                $elem->setLinkedProductId($relatedProductId);
                $elem->setLinkTypeId(self::RELATED_PRODUCT_ID);
                
                // Add new item to collection
                $collection->addItem($elem);
            }
        }
        
        // Save collection
        $collection->save();
        
        return $collection;
    }
    
    public function execute()
    {
        if(!$this->scopeConfig->getValue('jobs/cron_related_products/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)){
            return $this;
        }
        /**
         * Get product relations from DB
         * 
         * for example if:
         * 
         * 1 => (2,5)
         * 2 => 3
         * 
         * Then:
         * 
         * $relations =  [
         *     1 => [2,5],
         *     2 => [3]
         * ]
         */
        $relations = $this->_getRelatedProductsOnDatabase();
        
        if (count($relations) > 0) {
            /**
             * Get all relations needed
             * 
             * If:
             * 
             * $relations =  [
             *     1 => [2,5],
             *     2 => [3]
             * ]
             * 
             * Then:
             * 
             * $allRelations = [
             *     1 => [1,2,3,5] 
             *     2 => [1,2,3,5]
             *     3 => [1,2,3,5]
             *     5 => [1,2,3,5]
             * ]
             */
            $allRelations = $this->_getAllRelations($relations);

            /**
             * Keeps only the relations that are not present already in DB
             * And deletes relations between the same product
             * 
             * If:
             * 
             * $allRelations = [
             *     1 => [1,2,3,5] 
             *     2 => [1,2,3,5]
             *     3 => [1,2,3,5]
             *     5 => [1,2,3,5]
             * ]
             * 
             * Then:
             * 
             * $newDbRelations = [
             *     1 => [3] 
             *     2 => [1,5]
             *     3 => [1,2,5]
             *     5 => [1,2,3]
             * ]
             *  
             */
            $newDbRelations = $this->_getRelationsToCreateOnDb($relations, $allRelations);

            if (count($newDbRelations) > 0) {
                // Create empty collection, add needed items, and save collection
                $this->_createNewCollection($newDbRelations);
                echo "Products relations saved!.\n";
            } else {
                echo "All products relations are already created.\n";
            }
        } else {
            echo "No related products on DB.\n";
        }
    }
}