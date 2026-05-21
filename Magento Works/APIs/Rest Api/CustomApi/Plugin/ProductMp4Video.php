<?php

namespace Codilar\CustomApi\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface ;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class ProductMp4Video
{
    private const  PATH_TO_MP4_CONFIG = "footer_config/mp_4url/enable";

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Plugin to validate mp4 video
     *
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $result
     * @param string $sku
     * @param bool $editMode
     * @param int | null $storeId
     * @param bool $forceReload
     * @return ProductInterface
     */
    public function afterGet(
        ProductRepositoryInterface $subject,
        $result,
        $sku,
        $editMode = false,
        $storeId = null,
        $forceReload = false
    ) {
        if ($storeId === null) {
            $storeId = $this->getCurrentStore();
            $isEnable = $this->scopeConfig->getValue(self::PATH_TO_MP4_CONFIG, ScopeInterface::SCOPE_STORE, $storeId);
        } else {
            $isEnable = $this->scopeConfig->getValue(self::PATH_TO_MP4_CONFIG, ScopeInterface::SCOPE_STORE, $storeId);
        }
        if (!$isEnable) {
            $mp4Attr = $result->getCustomAttribute("mp4_url");
            if (!empty($mp4Attr)) {
                $result->setCustomAttribute($mp4Attr->getAttributeCode(), '');
            }
        }
        return $result;
    }

    /**
     * Get current store
     *
     * @return int
     */

    public function getCurrentStore()
    {
        try {
            $currentStore = $this->storeManager->getStore();
            $storeId = $currentStore->getId();
        } catch (NoSuchEntityException $e) {
            $storeId = 0;
        }
        return $storeId;
    }
}
