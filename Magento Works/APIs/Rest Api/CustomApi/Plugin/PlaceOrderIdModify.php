<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Plugin;

use Magento\Quote\Api\CartManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class PlaceOrderIdModify
{
    /**
     * @var OrderRepositoryInterface
     */
    protected OrderRepositoryInterface $orderRepository;

    /**
     * @var OrderFactory
     */
    protected OrderFactory $orderFactory;
    private OrderCollectionFactory $orderCollectionFactory;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderFactory $orderFactory,
        OrderCollectionFactory $orderCollectionFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderFactory = $orderFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * @param CartManagementInterface $subject
     * @param $result
     * @return array
     */
    public function afterPlaceOrder(CartManagementInterface $subject, $result)
    {
        $orderDetail = [];
        $order = $this->orderRepository->get($result);
        $orderDetail['orderId'] = $result;
        $orderDetail['orderIncrementId'] = $order->getIncrementId();
        $firstOrder = $this->isCustomerFirstAppOrder($orderDetail['orderId']);
        $this->updateOrderDeviceType($order->getIncrementId(), 'mobile');
        $deliveryDate = $order->getExtensionAttributes('amdeliverydate')->getAmdeliverydate()->getDate();
        if ($deliveryDate) {
            $orderDetail['deliveryDate'] = $deliveryDate;
        }
        $orderDetail['first_order'] = $firstOrder ? 'true' : 'false';
        return $orderDetail;
    }

    /**
     * Set the value to order_device_type column
     *
     * @param $incrementId
     * @param $status
     * @return void
     */
    public function updateOrderDeviceType($incrementId, $status)
    {
        // Load order by increment ID using order factory
        $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        // Set the custom field order_device_type
        $order->setData('order_device_type', $status);
        // Save the order
        $this->orderRepository->save($order);
    }
    /**
     * @param $orderId
     * @return bool
     */
    public function isCustomerFirstAppOrder($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        $customerEmail = $order->getCustomerEmail();

        // Load order collection for the customer's email from sales_order
        $orderCollection = $this->orderCollectionFactory->create();
        $orderCollection->addFieldToFilter('customer_email', ['eq' => $customerEmail]);
        $orderCollection->addFieldToFilter('order_device_type', ['eq' => 'mobile']);
        if($orderCollection->getSize() === 0){
            return true;
        }
        return false;
    }
}
