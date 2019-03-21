<?php
namespace Funimation\Bundle\Controller\Adminhtml\Product\Initialization\Helper\Plugin;

class Bundle extends \Magento\Bundle\Controller\Adminhtml\Product\Initialization\Helper\Plugin\Bundle {


    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function processBundleOptionsData(\Magento\Catalog\Model\Product $product)
    {
        $bundleOptionsData = $product->getBundleOptionsData();
        if (!$bundleOptionsData) {
            return;
        }
        $options = [];
        foreach ($bundleOptionsData as $key => $optionData) {
            if ((bool)$optionData['delete']) {
                continue;
            }

            $option = $this->optionFactory->create(['data' => $optionData]);
            $option->setSku($product->getSku());
            $option->setOptionId(null);

            $links = [];
            $bundleLinks = $product->getBundleSelectionsData();
            if (empty($bundleLinks[$key])) {
                continue;
            }

            foreach ($bundleLinks[$key] as $linkData) {
                if ((bool)$linkData['delete']) {
                    continue;
                }
                $link = $this->linkFactory->create(['data' => $linkData]);

                /** save option price for d */
                //if ((int)$product->getPriceType() !== \Magento\Bundle\Model\Product\Price::PRICE_TYPE_DYNAMIC) {
                    if (array_key_exists('selection_price_value', $linkData)) {
                        $link->setPrice($linkData['selection_price_value']);
                    }
                    if (array_key_exists('selection_price_type', $linkData)) {
                        $link->setPriceType($linkData['selection_price_type']);
                    }
                //}

                $linkProduct = $this->productRepository->getById($linkData['product_id']);
                $link->setSku($linkProduct->getSku());
                $link->setQty($linkData['selection_qty']);

                if (array_key_exists('selection_can_change_qty', $linkData)) {
                    $link->setCanChangeQuantity($linkData['selection_can_change_qty']);
                }
                $links[] = $link;
            }
            $option->setProductLinks($links);
            $options[] = $option;
        }

        $extension = $product->getExtensionAttributes();
        $extension->setBundleProductOptions($options);
        $product->setExtensionAttributes($extension);
        return;
    }



}