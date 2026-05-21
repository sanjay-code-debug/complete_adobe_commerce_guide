<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\OrderReturnInfo;
use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;
use Mageplaza\RMA\Helper\Data;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Codilar\CustomApi\Model\OrderTracking;
use DateTime;

class OrderReturnInformation implements OrderReturnInfo
{
    /**
     * @var Data
     */
    protected Data $data;

    /**
     * @var OrderRepositoryInterface
     */
    protected OrderRepositoryInterface $orderRepository;

    /**
     * @var OrderFactory
     */
    protected OrderFactory $orderFactory;

    /**
     * Get the return time
     */
    public const AFTER_DELIVERY_TIME_IS_RETURN = "mprma/request/order_time_return";

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var OrderTracking
     */
    private OrderTracking $orderTracking;

    /**
     * @param Data $data
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderFactory $orderFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param OrderTracking $orderTracking
     */
    public function __construct(
        Data $data,
        OrderRepositoryInterface $orderRepository,
        OrderFactory $orderFactory,
        ScopeConfigInterface $scopeConfig,
        OrderTracking $orderTracking
    ) {
        $this->data = $data;
        $this->orderRepository = $orderRepository;
        $this->orderFactory = $orderFactory;
        $this->scopeConfig = $scopeConfig;
        $this->orderTracking = $orderTracking;
    }

    /**
     * Get the order return info
     *
     * @param string $orderId
     * @return mixed
     * @throws LocalizedException
     */
    public function orderReturnInfo(string $orderId)
    {

        $deliveryDate = $this->getTimeIsReturned($orderId);
        $order = $this->orderRepository->get($orderId);
        $orderObject = $this->orderFactory->create()->load($orderId);
        $returnInfo = [];
        foreach ($order->getAllVisibleItems() as $item) {
            $returnableQty = 0;
            $productId = $item->getProductId();
            $returnable = $this->data->canReturnProduct($productId, $orderObject);
            if ($returnable != false && $deliveryDate) {
                $returnable = true;
                $returnableQty = $this->getAvailableQtyToReturn($item);
            }

            if ($returnableQty == 0) {
                $returnable = false;
            }
            $returnInfo[$productId] = [
                'returnable' => $returnable,
                'returnableqty' => $returnableQty,
            ];
        }
        return json_encode($returnInfo);
    }

    /**
     * Get the return time and check the order is returnable or not
     *
     * @param $orderId
     * @return bool
     * @throws LocalizedException
     * @throws Exception
     */
    public function getTimeIsReturned($orderId)
    {
        $returnTime = $this->scopeConfig->getValue(
            self::AFTER_DELIVERY_TIME_IS_RETURN,
            ScopeInterface::SCOPE_STORE
        );
        $deliveryTime = $this->orderTracking->getOrderTracingDetails($orderId);
        $deliveryTime = json_decode($deliveryTime, true);
        $deliveredTimestamp = null;
        if (isset($deliveryTime['post_shipping_info']['key_milestones']['delivered'])) {
            $deliveredTimestamp = $deliveryTime['post_shipping_info']['key_milestones']['delivered'];
        }
        if ($deliveredTimestamp != null) {
            $dateTime = new DateTime($deliveredTimestamp);
            $datePart = $dateTime->format('Y-m-d');
            $dateTime = new DateTime($datePart);
            $dateTime->modify('+' . $returnTime . ' day');
            $newDate = $dateTime->format('Y-m-d');
            // Get the current date
            $currentDate = (new DateTime())->format('Y-m-d');
            if ($newDate > $currentDate) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Get available quantity to return
     *
     * @param $item
     * @return float|int
     */
    private function getAvailableQtyToReturn($item)
    {
        return $item->getQtyOrdered() * 1 - $item->getMpQtyRma() * 1;
    }
}
