<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SetAppVersionAndModelType implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();

        // Retrieve values from the quote
        $appVersion = $quote->getData('app_version');
        $iphoneModelType = $quote->getData('iphone_model_type');

        // Set values in the order
        $order->setData('app_version', $appVersion);
        $order->setData('iphone_model_type', $iphoneModelType);
    }
}
