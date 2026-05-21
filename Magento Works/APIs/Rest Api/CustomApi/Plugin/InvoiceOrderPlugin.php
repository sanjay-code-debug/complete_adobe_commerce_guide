<?php

namespace Codilar\CustomApi\Plugin;

use Magento\Sales\Api\InvoiceOrderInterface as  Subject;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Email\Container\OrderIdentity;
use Magento\Sales\Model\Order\InvoiceRepository;
use Magento\Sales\Model\ResourceModel\Order as OrderResource;

/**
 * Order sync invoice class
 */
class InvoiceOrderPlugin
{
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;
    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoiceRepository;
    /**
     * @var OrderResource
     */
    private OrderResource $orderResource;
    /**
     * @var OrderIdentity
     */
    private OrderIdentity $identityContainer;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param InvoiceRepository $invoiceRepository
     * @param OrderResource $orderResource
     * @param OrderIdentity $identityContainer
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InvoiceRepository $invoiceRepository,
        OrderResource $orderResource,
        OrderIdentity $identityContainer
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->orderResource = $orderResource;
        $this->identityContainer = $identityContainer;
    }

    /**
     * Order sync
     *
     * @param Subject $invoiceOrder
     * @param int $result
     * @param int $orderId
     * @return int
     */
    public function afterExecute(Subject $invoiceOrder, $result, $orderId)
    {
        try {
            $order = $this->orderRepository->get($orderId);
            $invoice = $this->invoiceRepository->get($result);
            if ((int) $invoice->getEmailSent() && !(int)$order->getEmailSent()) {
                $order->setSendEmail($this->identityContainer->isEnabled());
                $order->setEmailSent(true);
                $this->orderResource->saveAttribute($order, ['send_email', 'email_sent']);
            }
        } catch (\Exception $e) {
            return $result;
        }
        return $result;
    }
}
