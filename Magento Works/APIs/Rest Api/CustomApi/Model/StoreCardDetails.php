<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model;

use CheckoutCom\Magento2\Model\Service\VaultHandlerService;
use Codilar\CustomApi\Api\StoreCardDetailInterface;
use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Vault\Api\PaymentTokenRepositoryInterface;
use Magento\Vault\Model\PaymentTokenManagement;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class StoreCardDetails implements StoreCardDetailInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * $vaultHandler field
     *
     * @var VaultHandlerService $vaultHandler
     */
    private VaultHandlerService $vaultHandler;

    /**
     * @var CustomerRepositoryInterface
     */
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @var PaymentTokenManagement
     */
    private PaymentTokenManagement $tokenManagement;

    /**
     * @var PaymentTokenRepositoryInterface
     */
    private PaymentTokenRepositoryInterface $tokenRepository;

    /**
     * @var ResourceConnection
     */
    protected ResourceConnection $resourceConnection;
    /**
     * Get api url
     */
    public const API_URL = 'footer_config/store_card/api_url';

    /**
     * Get secret key
     */
    public const SECRET_KEY = 'footer_config/store_card/secret_key';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param VaultHandlerService $vaultHandler
     * @param CustomerRepositoryInterface $customerRepository
     * @param PaymentTokenManagement $tokenManagement
     * @param PaymentTokenRepositoryInterface $tokenRepository
     * @param ResourceConnection $resourceConnection
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        VaultHandlerService $vaultHandler,
        CustomerRepositoryInterface $customerRepository,
        PaymentTokenManagement $tokenManagement,
        PaymentTokenRepositoryInterface $tokenRepository,
        ResourceConnection $resourceConnection,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->vaultHandler = $vaultHandler;
        $this->customerRepository = $customerRepository;
        $this->tokenManagement = $tokenManagement;
        $this->tokenRepository = $tokenRepository;
        $this->resourceConnection = $resourceConnection;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * Implemented method
     *
     * @param int $customerId
     * @param string $type
     * @param string $number
     * @param string $expiry_month
     * @param string $expiry_year
     * @param string $cvv
     * @return string|boolean
     * @throws LocalizedException
     * @throws Exception
     */
    public function storedPaymentMethods(
        int $customerId,
        string $type,
        string $number,
        string $expiry_month,
        string $expiry_year,
        string $cvv
    ) {
        try {
            $data = [
            'type' => "card",
            'number' => $number,
            'expiry_month' => $expiry_month,
            'expiry_year' => $expiry_year,
            'cvv' => $cvv
            ];

            $customer = $this->customerRepository->getById($customerId);
            $email = $customer->getEmail();

            $token = $this->getTokenByCardDetails($data);
            if ($token === null) {
                return "invalid card! please enter valid card "; // Return "Invalid Card" when the token is null
            }
            $result = $this->vaultHandler->setCardToken($token)
                ->setCustomerId($customerId)
                ->setCustomerEmail($email)
                ->authorizeTransaction();
            $success = $result->saveCard();
            if ($success) {
                return true;
            } else {
                return "The card could not be saved.";
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Issue with save the card'));
        }
    }

    /**
     * Create token by passing card details
     *
     * @param array $data
     * @return mixed|void
     * @throws LocalizedException
     */
    private function getTokenByCardDetails(array $data)
    {
        try {
            $curl = curl_init();
            $jsonPayload = json_encode($data);
            $apiUrl = $this->getApiUrl();
            $secretKey = $this->getSecretKey();

            curl_setopt_array($curl, [
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$jsonPayload,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $secretKey
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);
            $responseData = json_decode($response, true);
            if (isset($responseData['token'])) {
                return $responseData['token'];
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Issue with token generation'));
        }
    }
    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    private function getApiUrl()
    {
        $websiteId =  $this->storeManager->getStore()->getWebsiteId();
        return $this->scopeConfig->getValue(
            self::API_URL,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    private function getSecretKey()
    {
        $websiteId =  $this->storeManager->getStore()->getWebsiteId();
        return $this->scopeConfig->getValue(
            self::SECRET_KEY,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get the card details
     *
     * @param int $customerId
     * @return array
     */
    public function fetchCardDetails(int $customerId): array
    {
        $detailsArray = [];
        $tokens = $this->tokenManagement->getVisibleAvailableTokens($customerId);
        foreach ($tokens as $token) {
            $data = $token->getData();
            $details = $data['details'];
            $publicHash = $data['public_hash'];
            $cardDetails = json_decode($details, true);
            $cardDetails['public_hash'] = $publicHash;
            $cardDetails['src_id'] = $data['gateway_token'];
            $detailsArray[] = $cardDetails;
        }
        return $detailsArray;
    }

    /**
     * Delete the card
     *
     * @param int $customerId
     * @param string $hashValue
     * @return mixed
     * @throws LocalizedException
     */
    public function deleteCard(int $customerId, string $hashValue)
    {
        try {
            $connection = $this->resourceConnection->getConnection();
            $tableName = $this->resourceConnection->getTableName('vault_payment_token');
            $select = $connection->select()
                ->from($tableName, ['public_hash'])
                ->where('customer_id = ?', $customerId);
            $publicHash = $connection->fetchCol($select);
            $matchFound = false;
            foreach ($publicHash as $hash) {
                if ($hash == $hashValue) {
                    $paymentToken =  $this->tokenManagement->getByPublicHash($hash, $customerId);
                    $this->tokenRepository->delete($paymentToken);
                    $matchFound = true;
                    break;
                }
            }
            if (!$matchFound) {
                return 'No card found matching the given count sequence.';
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Not able to delete the card'));
        }
        return true;
    }
}
