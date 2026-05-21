<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\OrderTrackingInterface;
use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\Status\History\CollectionFactory as HistoryCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Shipment\Track\CollectionFactory as TrackCollectionFactory;

class OrderTracking implements OrderTrackingInterface
{
    /**
     * Get client id
     */
    public const CLIENT_ID = 'carriyo/api_credentials/client_id';

    /**
     * Get client secret key
     */
    public const CLIENT_SECRET_KEY = 'carriyo/api_credentials/client_secret';

    /**
     * Get tenant_id
     */
    public const TENANT_ID = 'carriyo/api_credentials/tenant_id';

    /**
     * Get x_api_key
     */
    public const X_API_KEY = 'carriyo/api_credentials/api_key';

    /**
     * Get api_domain
     */
    public const API_DOMAIN = 'carriyo/api_endpoints/api_url';

    /**
     * Get api_oauth_domain
     */
    public const API_OAUTH_DOMAIN = 'footer_config/order_tracking/api_oauth_url';

    /**
     * @var EncryptorInterface
     */
    private EncryptorInterface $decryptor;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var Order
     */
    protected Order $order;

    /**
     * @var HistoryCollectionFactory
     */
    private $historyCollectionFactory;

    /**
     * @var TrackCollectionFactory
     */
    private $trackCollectionFactory;

    /**
     * Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param EncryptorInterface $decryptor
     * @param Order $order
     * @param HistoryCollectionFactory $historyCollectionFactory
     * @param TrackCollectionFactory $trackCollectionFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $decryptor,
        Order $order,
        HistoryCollectionFactory $historyCollectionFactory,
        TrackCollectionFactory $trackCollectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->decryptor = $decryptor;
        $this->order = $order;
        $this->historyCollectionFactory = $historyCollectionFactory;
        $this->trackCollectionFactory = $trackCollectionFactory;
    }

    /**
     * Get order tracking details
     *
     * @param string $increment_id
     * @param string $billing_lastname
     * @param string $email
     * @return mixed
     * @throws LocalizedException
     */
    public function orderTrackingDetails(string $increment_id, string $billing_lastname, string $email)
    {
        $email = strtolower($email);
        $orderInfo = $this->order->loadByIncrementId($increment_id);
        $orderId = $orderInfo ->getId();
        $emailFromOrder = str_replace(' ', '', strtolower($orderInfo->getCustomerEmail()));
        $billingLastName = str_replace(' ', '', strtolower($orderInfo->getBillingAddress()->getLastname()));
        $billing_lastname = str_replace(' ', '', strtolower($billing_lastname));
        if ($email == $emailFromOrder && $billingLastName == $billing_lastname) {
            return  $this->getOrderTracingDetails($orderId);
        } else {
            throw new LocalizedException(__('Sorry! No Order Tracking Available For This Order.'));
        }
    }

    /**
     * Get auth token
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function getAuthToken()
    {
        try {
            $curl = curl_init();
            $oauthUrl = $this->getTokenOauthUrl();
            $clientId = $this->getClientId();
            $clientSecret = $this->getClientSecretKey();

            $postData = json_encode([
                "client_id" => $clientId,
                "client_secret" => $clientSecret
            ]);
            curl_setopt_array($curl, [
                CURLOPT_URL => $oauthUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json'
                ],
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            $responseData = json_decode($response, true);
            if (isset($responseData['access_token'])) {
                return $responseData['access_token'];
            }
        } catch (Exception $e) {
            throw new LocalizedException(__('Issue with token generation'));
        }
    }

    /**
     * Get tracking order details
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function getOrderTracingDetails($orderId)
    {
        try {
            $curl = curl_init();
            $token = $this->getAuthToken();
            $tenantId = $this->getTenantId();
            $xApiKey = $this->getXApiKey();
            $apiDomain = $this->getApiDomain();
            $shipmentId = $this->getShipmentId($orderId);
            $url = $apiDomain . '/shipments/' . $shipmentId;
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'x-api-key: ' . $xApiKey,
                    'tenant-id: ' . $tenantId,
                    'Authorization: Bearer ' . $token
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (Exception $e) {
            throw new LocalizedException(__('Issue with getting the order tracking details'));
        }
    }

    /**
     * Get shipment id by using order id
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function getShipmentId($orderId)
    {
        try {
            $trackCollection = $this->trackCollectionFactory->create();
            $trackCollection->addFieldToSelect(['track_number', 'parent_id']);
            $trackCollection->addFieldToFilter('order_id', $orderId);
            $result = $trackCollection->getFirstItem()->getData();
            if (empty($result['track_number'])) {
                $trackingNumber = $this->getTrackingNumber($orderId);
            } else {
                $trackingNumber = $this->getTrackingNumber($result['parent_id']);
            }
            if (empty($result['track_number'])) {
                if (empty($trackingNumber)) {
                    return 'Sorry! No Order Tracking Available For This Order.';
                } else {
                    return $trackingNumber;
                }
            }
            return $result['track_number'] ?? $trackingNumber;
        } catch (Exception $e) {
            throw new LocalizedException(__('For the given order id, there is no tracking number'));
        }
    }

    /**
     * Get tracking number from sales_order_status_history table of comment field
     *
     * @param $parentId
     * @return string
     * @throws LocalizedException
     */
    public function getTrackingNumber($parentId) {
        try {
            $historyCollection = $this->historyCollectionFactory->create();
            $historyCollection->addFieldToSelect('comment');
            $historyCollection->addFieldToFilter('parent_id', $parentId);
            $historyCollection->addFieldToFilter('comment', ['like' => '%Carriyo DraftShipmentId#%']);
            $historyCollection->getSelect()
                ->columns(['extracted_value' => new \Zend_Db_Expr(
                    "SUBSTRING_INDEX(SUBSTRING_INDEX(comment, 'Carriyo DraftShipmentId# ', -1), ' ', 1)"
                )]);
            return $historyCollection->getFirstItem()->getExtractedValue();
        } catch (Exception $e) {
            throw new LocalizedException(__('An error occurred while retrieving the tracking number.'));
        }
    }

    /**
     * Get client id
     *
     * @return mixed
     */
    public function getClientId()
    {
        return $this->scopeConfig->getValue(
            self::CLIENT_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get client secret key
     *
     * @return mixed
     */
    public function getClientSecretKey()
    {
        return (string)$this->decryptor->decrypt(
            $this->scopeConfig->getValue(
                self::CLIENT_SECRET_KEY,
                ScopeInterface::SCOPE_STORE
            )
        );
    }

    /**
     * Get the carriyo oauth domain
     *
     * @return mixed
     */
    public function getTokenOauthUrl()
    {
        return $this->scopeConfig->getValue(
            self::API_OAUTH_DOMAIN,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get the carriyo api domain
     *
     * @return mixed
     */
    public function getApiDomain()
    {
        return $this->scopeConfig->getValue(
            self::API_DOMAIN,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get the tenant_id
     *
     * @return mixed
     */
    public function getTenantId()
    {
        return $this->scopeConfig->getValue(
            self::TENANT_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get x_api_key
     *
     * @return mixed
     */
    public function getXApiKey()
    {
        return $this->scopeConfig->getValue(
            self::X_API_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }
}
