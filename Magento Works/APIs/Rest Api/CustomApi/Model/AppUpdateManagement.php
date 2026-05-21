<?php

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\AppUpdateManagementInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Codilar\CustomApi\Api\Data\AppUpdateInterfaceFactory;

/**
 * App update management
 */
class AppUpdateManagement implements AppUpdateManagementInterface
{
    private ScopeConfigInterface $scopeConfig;
    private StoreManagerInterface $storeManager;
    private AppUpdateInterfaceFactory $appUpdateInterfaceFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param AppUpdateInterfaceFactory $appUpdateInterfaceFactory
     *
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        AppUpdateInterfaceFactory $appUpdateInterfaceFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->appUpdateInterfaceFactory = $appUpdateInterfaceFactory;
    }
    /**
     * {@inheritdoc}
     */
    public function getAppUpdateDetails($customerAppVersion)
    {
        $appUpdate = $this->appUpdateInterfaceFactory->create();
        try {
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = 0;
        }
        $forcedUpdateVersion = $this->scopeConfig->getValue(self::FORCED_UPDATE_VERSION_CONFIG,
            ScopeInterface::SCOPE_WEBSITE, $websiteId);
        if ($customerAppVersion < $forcedUpdateVersion) {
            $appUpdate->setISForceUpdate(true);
        } else {
            $appUpdate->setISForceUpdate(false);
        }
       $appUpdate->setTitle($this->scopeConfig->getValue(self::UPDATE_TITLE_VERSION_,
            ScopeInterface::SCOPE_WEBSITE, $websiteId) ?? "");
        $appUpdate->setDescription($this->scopeConfig->getValue(self::UPDATE_DESCRIPTION_VERSION_CONFIG,
            ScopeInterface::SCOPE_WEBSITE, $websiteId) ?? "");
        return $appUpdate;
    }
}
