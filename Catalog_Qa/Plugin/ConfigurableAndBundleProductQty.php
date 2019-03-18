<?php
namespace Funimation\Catalog\Plugin;

class ConfigurableAndBundleProductQty
{
    
    protected $_bundlePriceFactory;
    
    protected $productQty = -1;
    protected $productIsInStock = false;
    
    protected $childQty = -1;
    protected $childIsInStock = false;
    protected $childManageStock = false;
    protected $childProcessed = false;
    
    public function __construct(\Magento\Bundle\Model\Product\PriceFactory $bundlePriceFactory) {
        $this->_bundlePriceFactory = $bundlePriceFactory;
    }
    
    
    public function afterGetExtensionAttributes($product, $result)
    {
        // Get stock item
        $stockItem = $result->getStockItem();
        
        if ($stockItem instanceof \Magento\CatalogInventory\Model\Stock\Item) {
            
            // Check if product is configurable
            if($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                /*************************/
                /* Configurable Products */
                /*************************/
                
                // Get child products
                $childProducts = $product->getTypeInstance()->getUsedProducts($product);
                
                // Init product values
                $this->productQty = $stockItem->getQty();
                $this->productIsInStock = $stockItem->getIsInStock();
                
                // Loop child products
                foreach ($childProducts as $childProduct) {
                    // Get attributes from child product
                    $this->getQtyFromChildProduct($childProduct);
                    
                    if ($this->childProcessed) {
                        
                        // Get greater quantity from children
                        if ($this->childQty > $this->productQty) {
                            $this->productQty = $this->childQty;
                        }
                        
                        // Process is in stock
                        // If the productIsInStock is false, to avoid processing multiple times
                        // and the child is in stock or child manage stock is true
                        // then set product is in stock flag to true
                        if (!$this->productIsInStock
                            && ($this->childIsInStock || !$this->childManageStock)    
                        ) {
                            $this->productIsInStock = true;
                        }
                    }
                }
                
                // Set product values
                $stockItem->setQty($this->productQty);
                $stockItem->setIsInStock($this->productIsInStock);
            } else if ($product->getTypeId() == \Magento\Bundle\Model\Product\Type::TYPE_CODE) {
                /*******************/
                /* Bundle Products */
                /*******************/

                // Get child products
                $typeInstance = $product->getTypeInstance();
                $childProducts = $typeInstance->getSelectionsCollection(
                    $typeInstance->getOptionsIds($product),
                    $product
                );

                /*
                $bundlePriceFactory = $this->_bundlePriceFactory->create();
                $bundlePrice = number_format(min($bundlePriceFactory->getTotalPrices($product)), 2, '.', '');

                $product->setPrice($bundlePrice);
                */
                $product->setPrice($product->getPriceInfo()->getPrice(\Magento\Bundle\Pricing\Price\BundleRegularPrice::PRICE_CODE)->getAmount()->getValue());
                $product->setFinalPrice($product->getPriceInfo()->getPrice(\Magento\Bundle\Pricing\Price\FinalPrice::PRICE_CODE)->getAmount()->getValue());
                // Init product values
                $this->productQty = null;
                $this->productIsInStock = true;

                // Loop child products
                foreach ($childProducts as $childProduct) {
                    // Get attributes from child product
                    $this->getQtyFromChildProduct($childProduct);

                    if ($this->childProcessed) {

                        // Get greater quantity from children
                        if ((is_null($this->productQty)) || ($this->childQty < $this->productQty)) {
                            $this->productQty = $this->childQty;
                        }

                        // Process is in stock
                        // All children must be in stock
                        if (!$this->childIsInStock && $this->childManageStock) {
                            $this->productIsInStock = false;
                        }

                    }
                }

                if (is_null($this->productQty)) {
                    $this->productQty = 0;
                }

                // Set product values
                $stockItem->setQty($this->productQty);
                $stockItem->setIsInStock($this->productIsInStock);
            }
        }
        
        return $result;
    }
    
    protected function getQtyFromChildProduct($product)
    {
        // Flag child processed
        $this->childProcessed = false;
        
        // Load product
        $product->load($product->getId());         
        
        // Get extension attributes
        $extensionAttributes = $product->getExtensionAttributes();
        
        // Get stock item
        $stockItem = $extensionAttributes->getStockItem();

        if ($stockItem instanceof \Magento\CatalogInventory\Model\Stock\Item) {
            // Flag child processed
            $this->childProcessed = true;
            
            // Is in stock
            $this->childIsInStock = $stockItem->getIsInStock();
            
            // Qty
            $this->childQty = !is_null($stockItem->getQty()) ? $stockItem->getQty() : 0;

            // Manage stock
            $this->childManageStock = $stockItem->getManageStock();
        }
    }
}