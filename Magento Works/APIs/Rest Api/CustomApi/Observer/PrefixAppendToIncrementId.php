<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class PrefixAppendToIncrementId implements ObserverInterface
{
    /**
     * Get config value for append text
     */
    public const APPEND_MESSAGE = "footer_config/prefix_message/append_text";

    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        /**
         * @var \Magento\Sales\Model\Order $order
         */
        $order = $observer->getEvent()->getOrder();
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';

        if (preg_match('#^/rest/[^/]+/V1/carts/mine/order$#', $currentUrl)) {
            $prefix = $this->getAppendMessage($order->getStoreId());
            $incrementId = $order->getIncrementId();
            if (strpos($incrementId, $prefix) === false) {
                $incrementIdWithoutStoreId = ltrim($incrementId, (string)$order->getStoreId());
                // $prefix is not present in $incrementId, so concatenate it
                $incrementIdWithoutZeros = ltrim($incrementIdWithoutStoreId, '0');
                $newIncrementId = $prefix . $incrementIdWithoutZeros;
                $order->setIncrementId($newIncrementId);
            }
        } elseif (preg_match('#^/rest/[^/]+/V1/guest-carts/(\w+)/order$#', $currentUrl)) {
                $prefix = $this->getAppendMessage($order->getStoreId());
                $order = $observer->getEvent()->getOrder();
                $incrementId = $order->getIncrementId();
            if (strpos($incrementId, $prefix) === false) {
                $incrementIdWithoutStoreId = ltrim($incrementId, (string)$order->getStoreId());
                // $prefix is not present in $incrementId, so concatenate it
                $incrementIdWithoutZeros = ltrim($incrementIdWithoutStoreId, '0');
                $newIncrementId = $prefix . $incrementIdWithoutZeros;
                $order->setIncrementId($newIncrementId);
            }
        } elseif (strpos($currentUrl, '/rest/V1/carts/mine/order') !== false) {
            $prefix = $this->getAppendMessage($order->getStoreId());
            $incrementId = $order->getIncrementId();
            if (strpos($incrementId, $prefix) === false) {
                // $prefix is not present in $incrementId, so concatenate it
                $incrementIdWithoutZeros = ltrim($incrementId, '0');
                $newIncrementId = $prefix . $incrementIdWithoutZeros;
                $order->setIncrementId($newIncrementId);
            }
        } elseif (preg_match('#/rest/V1/guest-carts/(\w+)/order#', $currentUrl, $matches)) {
            $prefix = $this->getAppendMessage($order->getStoreId());
            $order = $observer->getEvent()->getOrder();
            $incrementId = $order->getIncrementId();
            if (strpos($incrementId, $prefix) === false) {
                // $prefix is not present in $incrementId, so concatenate it
                $incrementIdWithoutZeros = ltrim($incrementId, '0');
                $newIncrementId = $prefix . $incrementIdWithoutZeros;
                $order->setIncrementId($newIncrementId);
            }
        }
    }

    /**
     * Get the append message from config
     *
     * @return string
     */
    public function getAppendMessage($storeId)
    {
        return $this->scopeConfig->getValue(
            self::APPEND_MESSAGE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
