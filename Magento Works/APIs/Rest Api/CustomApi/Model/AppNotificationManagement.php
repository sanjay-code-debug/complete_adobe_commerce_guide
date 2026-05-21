<?php

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\AppNotificationManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Klaviyo\Reclaim\Helper\ScopeSetting;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Codilar\CustomApi\Model\PatchCurl;
use Codilar\CustomApi\Model\PatchCurlFactory;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\WebsiteFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Codilar\PushNotification\Model\PushNotification;

class AppNotificationManagement implements AppNotificationManagementInterface
{
    public const PROFILE_UPDATE_API = 'klaviyo_reclaim_general/klaviyo_notification/profile_update_api';
    public const REVISION_DATE = 'klaviyo_reclaim_general/klaviyo_notification/revision_date';
    public const PROFILE_FETCH_API = 'klaviyo_reclaim_general/klaviyo_notification/profile_fetch_api';
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;
    /**
     * @var ScopeSetting
     */
    private ScopeSetting $scopeSetting;
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;
    /**
     * @var \Codilar\CustomApi\Model\PatchCurlFactory
     */
    private PatchCurlFactory $curlFactory;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var WebsiteFactory
     */
    private WebsiteFactory $websiteFactory;
    /**
     * @var Json
     */
    private Json $json;
    /**
     * @var PushNotification
     */
    private PushNotification $pushNotification;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ScopeSetting $scopeSetting
     * @param ScopeConfigInterface $scopeConfig
     * @param \Codilar\CustomApi\Model\PatchCurlFactory $curlFactory
     * @param LoggerInterface $logger
     * @param WebsiteFactory $websiteFactory
     * @param Json $json
     * @param PushNotification $pushNotification
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeSetting $scopeSetting,
        ScopeConfigInterface $scopeConfig,
        PatchCurlFactory $curlFactory,
        LoggerInterface $logger,
        WebsiteFactory $websiteFactory,
        Json $json,
        PushNotification $pushNotification
    ) {
        $this->storeManager = $storeManager;
        $this->scopeSetting = $scopeSetting;
        $this->scopeConfig = $scopeConfig;
        $this->curlFactory = $curlFactory;
        $this->logger = $logger;
        $this->websiteFactory = $websiteFactory;
        $this->json = $json;
        $this->pushNotification = $pushNotification;
    }

    /**
     * @inheritdoc
     */
    public function updateCutomerKlaviyo($externalId, $customerEmail)
    {
        try {
            $currentStoreId = $this->storeManager->getStore()->getId();
        } catch (NoSuchEntityException $e) {
            $currentStoreId = 1;
        }
        if ($this->scopeSetting->isEnabled($currentStoreId)) {
            $profileId = "";
            if (!empty($externalId)) {
                $profileId = $this->getProfileIdFromKlaviyo(null, $externalId);

            } elseif (!empty($customerEmail)) {
                $profileId = $this->getProfileIdFromKlaviyo($customerEmail, null);
            }
            if (!empty($profileId)) {
                $profileUpdateApi = trim($this->scopeConfig
                    ->getValue(self::PROFILE_UPDATE_API, ScopeInterface::SCOPE_STORE, $currentStoreId));
                $curl = $this->curlFactory->create();
                $curl->setHeaders([
                    "Authorization" => "Klaviyo-API-Key " . $this->scopeSetting->getPrivateApiKey($currentStoreId),
                    "revision" => $this->scopeConfig
                        ->getValue(self::REVISION_DATE, ScopeInterface::SCOPE_STORE, $currentStoreId)
                ]);
                $curl->addHeader("Content-Type", "application/json");
                $websiteModel = $this->websiteFactory->create();
                try {
                    $websiteModel->load($this->storeManager->getWebsite()->getId(), "website_id");
                    $abbreviateCode = $this->pushNotification->getAbbreviationCode($websiteModel);
                } catch (LocalizedException $e) {
                    $this->logger->info($e->getMessage());
                    return false;
                }

                $body = [
                    'data' => [
                        'type' => 'profile',
                        'id'=> $profileId,
                        'attributes' => [
                            'location' => [
                                'country' => $abbreviateCode['iso3_code']
                            ],
                            'properties' => [
                                'MagentoStore' => $this->storeManager->getStore()->getName(),
                                'MagentoWebsiteID'=> $this->storeManager->getWebsite()->getId()
                            ]
                        ]
                    ]
                ];
                $profileUpdateApi .= $profileId;
                $curl->patch($profileUpdateApi, $this->json->serialize($body));
                return $curl->getStatus() === 200;
            }

            return false;
        }
        return false;
    }

    /**
     * Get profile id from klaviyo
     *
     * @param string $email
     * @param string $externalId
     * @return string | null
     */
    public function getProfileIdFromKlaviyo($email, $externalId)
    {
        try {
            $currentStoreId = $this->storeManager->getStore()->getId();
        } catch (NoSuchEntityException $e) {
            $currentStoreId = 0;
        }
        $privateKey = $this->scopeSetting->getPrivateApiKey($currentStoreId);
        $privateKey = "Klaviyo-API-Key " . $privateKey;
        $fetchApi = trim($this->scopeConfig
            ->getValue(self::PROFILE_FETCH_API, ScopeInterface::SCOPE_STORE, $currentStoreId));
        $revision = trim($this->scopeConfig
            ->getValue(self::REVISION_DATE, ScopeInterface::SCOPE_STORE, $currentStoreId));
        $curl = $this->curlFactory->create();
        $curl->setHeaders([
            "Authorization" => $privateKey,
            "revision" => $revision
        ]);
        if (empty($email) && empty($externalId)) {
            return null;
        }
        if ($email !== null) {
            $fetchApi .= '?filter=equals(email,' .'"' . $email .'"'.')';
        } elseif ($externalId !== null) {
            $fetchApi .= '?filter=equals(external_id,' . "'" . $externalId . "'" .')';
        } else {
            return null;
        }
        $curl->get($fetchApi);
        $profileBody = $this->json->unserialize($curl->getBody());
        if (isset($profileBody['errors'])) {
            return null;
        }
        if (isset($profileBody['data'])) {
            foreach ($profileBody['data'] as $message) {
                if (isset($message['id'])) {
                    return $message['id'];
                }
            }
        }
        return null;
    }
}
