<?php
/**
 * ADOBE CONFIDENTIAL
 * ___________________
 *
 * Copyright 2022 Adobe
 * All Rights Reserved.
 *
 * NOTICE: All information contained herein is, and remains
 * the property of Adobe and its suppliers, if any. The intellectual
 * and technical concepts contained herein are proprietary to Adobe
 * and its suppliers and are protected by all applicable intellectual
 * property laws, including trade secret and copyright laws.
 * Adobe permits you to use and modify this file
 * in accordance with the terms of the Adobe license agreement
 * accompanying it (see LICENSE_ADOBE_PS.txt).
 * If you have received this file from a source other than Adobe,
 * then your use, modification, or distribution of it
 * requires the prior written permission from Adobe.
 */
declare(strict_types=1);

namespace CasioJP\Orico\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;

/**
 * Observer class for add additional email template variable.
 */
class AddAdditionOrderData implements ObserverInterface
{
    private const ORICO_PAYMENT_METHOD = 'orico';

    /**
     * Observer execute
     *
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        $transportObject = $event->getData('transportObject');
        /**
         * @var $order Order
         */
        $order = $transportObject->getData('order');
        if ($order) {
            $isOricoPayment = false;
            if ($order->getPayment()->getMethod() == self::ORICO_PAYMENT_METHOD) {
                $isOricoPayment = true;
            }
            $transportObject->addData(
                [
                    'is_orico_payment' => $isOricoPayment,
                ]
            );
        }

        return $this;
    }
}
