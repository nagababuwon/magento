<?php

namespace Funimation\Catalog\Cron;

use \Magento\Catalog\Model\ResourceModel\Product\Collection;


class SaleItemProduct
{
    /** @var \Magento\Catalog\Api\ProductRepositoryInterfaceFactory */
    protected  $productRepositoryFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    protected $_moduleHelper;
    protected $_cronLogsFactory;
    protected $_errors = [];

    /**
     * CustomAttributeProduct constructor.
     * @param \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(\Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
                                \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
                                \Magento\Framework\Api\FilterBuilder $filterBuilder,
                                \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                                \Born\Feed\Helper\Data $moduleHelper,
                                \Born\Feed\Model\CronLogsFactory $cronLogsFactory
                                ) {
        $this->productRepositoryFactory = $productRepositoryFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->_moduleHelper = $moduleHelper;
        $this->_cronLogsFactory = $cronLogsFactory;
    }

    /**
     * Send abandoned cart reminder emails.
     *
     * @return void
     */
    public function execute()
    {
        if(!$this->scopeConfig->getValue('jobs/cron_special_price/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)){
            return $this;
        }
        $isNotificationOn = (boolean)$this->scopeConfig->getValue('born_feed/cron_notification/active');
        $productRepository = $this->productRepositoryFactory->create();

        $filterProductsActive = $this->filterBuilder
            ->setField("status")
            ->setValue("1")
            ->setConditionType("eq")
            ->create();
        
        $this->searchCriteriaBuilder->addFilters([$filterProductsActive]);

        $searchCriteria = $this->searchCriteriaBuilder->create();

        $products = $productRepository->getList($searchCriteria);

        foreach ($products->getItems() as $product){
            try{
                $product = $this->_setCustomAttributes($product);

                /*
                this will unlink all configurable child product, change to using addAttributeUpdate
                $product->addAttributeUpdate('is_sale_item', $isSaleItem, $product->getStoreId() );

                */
                /*
                if ($product){
                    $product->save();
                }
                */

            }catch(\Exception $e){
                $this->_errors[] = $e->getMessage();
            }
        }
        if(is_array($this->_errors) && count($this->_errors) > 0 && $isNotificationOn){
            $emailTemplateVars = [];
            $emailTemplateVars['jobTitle'] = __('Is Sale Item Cron')->getText();
                $emailTemplateVars['errorLog'] = implode('. ',$this->_errors);
                $emailTemplateVars['cronDate'] = date('Y-m-d H:i:s');
                $result = $this->_moduleHelper->sendFailedNotificationEmail($emailTemplateVars);
        }
        if(count($this->_errors) > 0){
            $emailSent = 0;
            if(is_int($result)){
                $emailSent = $result;
            }else{
                if(is_array($result)){
                    $emailSent = isset($result['result']) ? $result['result']: 0;
                    if(isset($result['error'])){
                        $this->_errors[] = $result['error'];
                    }
                }
            }
            $this->_cronLogsFactory->create()->setTitle(__('Is Sale Item Cron')->getText())->setErrorNote(implode('. ',$this->_errors))->setEmailSent($emailSent)->save();
        }
        return $this;
    }

    protected function _setCustomAttributes($product)
    {
        $isSaleItem = 0;

        if ($product->getFinalPrice() < $product->getPrice()){
            $isSaleItem = 1;
        }

        $isSaleItemOld = $product->getCustomAttribute('is_sale_item');
        if ((isset($isSaleItemOld)) && ($isSaleItemOld->getValue() != $isSaleItem)){
            return false;
        }
        $product->addAttributeUpdate('is_sale_item', $isSaleItem, $product->getStoreId() );
        //$product->setCustomAttribute("is_sale_item", $isSaleItem);
        return $product;
    }

}
