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

namespace CasioJP\Orico\Observer;

use CasioJP\Orico\Helper\GetOrderStatus;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;

/**
 * Class for Orico Order status
 */
class OrderStatus implements ObserverInterface
{
    /**
     * @var GetOrderStatus
     */
    private $orderStatus;

    /**
     * @param GetOrderStatus $orderStatus
     */
    public function __construct(GetOrderStatus $orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    /**
     * Order save event
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();
        $method = $order->getPayment()->getMethod();
        $orderStatus = $this->orderStatus->getOrderStatus();
        try {
            if ($method =='orico' && !empty($orderStatus)) {
                $order->setStatus($orderStatus);
            }
        } catch (\Exception $e) {
            return;
        }
    }
}
