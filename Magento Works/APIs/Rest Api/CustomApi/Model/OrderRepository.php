<?php
// @codingStandardsIgnoreFile

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\Data\Message\MessageInterface;
use Codilar\CustomApi\Api\OrderRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Api\OrderRepositoryInterface as SalesOrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\WebsiteFactory;
use Magento\Sales\Model\OrderFactory;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @var Json
     */
    private Json $jsonSerializer;

    /**
     * @var OrderCollectionFactory
     */
    private OrderCollectionFactory $orderCollectionFactory;

    /**
     * @var SalesOrderRepositoryInterface
     */
    private SalesOrderRepositoryInterface $orderRepository;
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;
    /**
     * @var WebsiteFactory
     */
    private WebsiteFactory $websiteFactory;
    private OrderFactory $orderFactory;

    /**
     * @param ResourceConnection $resourceConnection
     * @param MessageInterface $message
     * @param Json $jsonSerializer
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param SalesOrderRepositoryInterface $orderRepository
     * @param StoreManagerInterface $storeManager
     */

    public function __construct(
        ResourceConnection $resourceConnection,
        MessageInterface $message,
        Json  $jsonSerializer,
        OrderCollectionFactory $orderCollectionFactory,
        SalesOrderRepositoryInterface $orderRepository,
        StoreManagerInterface $storeManager,
        WebsiteFactory $websiteFactory,
        OrderFactory $orderFactory
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->message = $message;
        $this->jsonSerializer = $jsonSerializer;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->storeManager = $storeManager;
        $this->websiteFactory = $websiteFactory;
        $this->orderFactory = $orderFactory;
    }

    /**
     * Add Payment Transaction ID while place the order
     *
     * @param string $orderId
     * @param string $authoriseTxnId
     * @param string $capturedTxnId
     * @param string $paymentId
     * @return MessageInterface
     */
    public function addTransaction($orderId, $authoriseTxnId, $capturedTxnId, $paymentId = null)
    {
        /**
         * check customer first app order
         *
         */
        $isCustomerFirstAppOrder = $this->isCustomerFirstAppOrder($orderId);

        try {
            if (empty($orderId)) {
                return $this->message->setMessage(__("OrderId is required"))->setStatus(false);
            }
            if (empty($authoriseTxnId)) {
                return $this->message->setMessage(__("Authorise transaction ID is required"))->setStatus(false);
            }
//            if (empty($capturedTxnId)) {
//                return $this->message->setMessage(__("Capture transaction ID is required"))->setStatus(false);
//            }
//            if (empty($paymentId)) {
//                return $this->message->setMessage(__("Payment ID is required"))->setStatus(false);
//            }

            if(!empty($paymentId)) {
                $this->addPaymentIdToOrder($orderId, $paymentId);
            }

            /* Get Payment Id From sales_order_payment */
            $getPaymentIdQuery = "SELECT entity_id from sales_order_payment where parent_id = '" . $orderId . "' ";
            $payment_id = $this->resourceConnection->getConnection()->fetchOne($getPaymentIdQuery);

            /* Insert Authorise Transaction */
            $insertauthoriseTxnQuery = "INSERT INTO sales_payment_transaction (parent_id, order_id, payment_id, txn_id, parent_txn_id, txn_type, is_closed) ";
            $insertauthoriseTxnQuery .= "VALUES(NULL, $orderId, $payment_id, '" . $authoriseTxnId . "', null, 'authorization', 1)";
            $this->resourceConnection->getConnection()->query($insertauthoriseTxnQuery);

            /* Get Parent Id From sales_payment_transaction */
            $getParentIdQuery = "SELECT transaction_id from sales_payment_transaction where order_id = '" . $orderId . "' AND txn_id = '" . $authoriseTxnId . "' ";
            $parent_id = $this->resourceConnection->getConnection()->fetchOne($getParentIdQuery);

            if (!empty($capturedTxnId)) {
                /* Insert Capture Transaction */
                $insertcapureTxnQuery = "INSERT INTO sales_payment_transaction (parent_id, order_id, payment_id, txn_id, parent_txn_id, txn_type, is_closed) ";
                $insertcapureTxnQuery .= "VALUES($parent_id, $orderId, $payment_id, '" . $capturedTxnId . "', '" . $authoriseTxnId . "', 'capture', 0)";
                $this->resourceConnection->getConnection()->query($insertcapureTxnQuery);

                /* adding capture id in invoice */
                $getInvoiceQuery = "SELECT entity_id from sales_invoice where order_id = '" . $orderId . "' ";
                $invoiceId = $this->resourceConnection->getConnection()->fetchOne($getInvoiceQuery);
                if ($invoiceId) {
                    $insertCaptureIdInInvoice = "UPDATE sales_invoice SET transaction_id =  '" . $capturedTxnId . "' where entity_id = '" . $invoiceId . "' ";
                    $this->resourceConnection->getConnection()->query($insertCaptureIdInInvoice);
                }
            }
            return $this->message->setMessage(__("Your transaction has been added successfully"))->setStatus(true)->setFirstOrder($isCustomerFirstAppOrder);
        } catch (\Exception $e) {
            return $this->message->setMessage(__("Something is went to wrong, please try again"))->setStatus(false)->setFirstOrder(false);
        }
    }

    /**
     * Add additional info to order payment
     *
     * @param int $orderId
     * @param string $paymentId
     * @return void
     */
    private function addPaymentIdToOrder($orderId, $paymentId)
    {
        $getPaymentAdditionalInfoQuery = "SELECT additional_information from sales_order_payment where parent_id = '" . $orderId . "' ";
        $additionalInfo = $this->resourceConnection->getConnection()->fetchOne($getPaymentAdditionalInfoQuery);
        if (!empty($additionalInfo)) {
            $additionalInfoArray = $this->jsonSerializer->unserialize($additionalInfo);
        } else {
            $additionalInfoArray = [];
        }
        if (!isset($additionalInfoArray['transaction_info']) && is_array($additionalInfoArray)) {
            $additionalInfoArray['transaction_info'] = [
                "id" => $paymentId
            ];
            $additionalInfoJson = $this->jsonSerializer->serialize($additionalInfoArray);
            try {
                $insertAdditionalInfoQuery = "UPDATE sales_order_payment SET additional_information =  '" . $additionalInfoJson . "' where parent_id = '" . $orderId . "' ";
                $this->resourceConnection->getConnection()->query($insertAdditionalInfoQuery);
            }catch (\Exception $exception) {}
        }
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
        if($orderCollection->getSize() === 1){
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function validateOrderStore($orderIncrementid)
    {
        if (!empty($orderIncrementid)) {
                $order = $this->orderFactory->create();
                $order->loadByIncrementId($orderIncrementid);
                if (empty($order->getId())) {
                    throw new LocalizedException(__("Sorry, order does not exist"));
                }
                $storeId = $this->storeManager->getStore()->getId();
                $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();
                $websiteModel = $this->websiteFactory->create();
                $websiteModel->load($websiteId, "website_id");
            if ($storeId !== $order->getStoreId()) {
                throw new LocalizedException(__("Order details unavailable in the current store. Please switch back to " . $websiteModel->getName() . " to view your order."));
            }
            return true;
        }
        return false;
    }
}
