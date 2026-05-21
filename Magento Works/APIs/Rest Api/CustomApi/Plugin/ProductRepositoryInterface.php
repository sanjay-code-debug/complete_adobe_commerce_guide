<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Plugin;

use Exception;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface as MagentoRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as PsrLogger;

class ProductRepositoryInterface
{
    /**
     * Get config value for postpay
     */
    public const POSTPAY_PRICE_LIMIT= 'footer_config/bnpl/postpay_price_limit';

    /**
     * Get config value for tabby
     */
    public const TABBY_PRICE_LIMIT= 'footer_config/bnpl/tabby_price_limit';

    /**
     * Check the postpay is active
     */
    public const IS_POSTPAY_ENABLED= 'footer_config/bnpl/enabled';

    /**
     * Check the tabby is active
     */
    public const IS_TABBY_ENABLED= 'payment/tabby_installments/active';

    public const POSTPAY = 'postpay';

    public const TABBY = 'tabby';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var PsrLogger
     */
    protected PsrLogger $logger;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param PsrLogger $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        PsrLogger $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * After get
     *
     * @param MagentoRepository $subject
     * @param ProductInterface $product
     * @return ProductInterface
     */
    public function afterGet(MagentoRepository $subject, ProductInterface $product)
    {
        $isPostAvailable = $this->checkCondition($product->getFinalPrice(), self::POSTPAY);
        $isTabbyAvailable = $this->checkCondition($product->getFinalPrice(), self::TABBY);

        $extensionAttributePostPay = $product->getExtensionAttributes()->setIsPostpayAvailable($isPostAvailable);
        $product->setExtensionAttributes($extensionAttributePostPay);
        $extensionAttributeTabby = $product->getExtensionAttributes()->SetIsTabbyAvailable($isTabbyAvailable);
        $product->setExtensionAttributes($extensionAttributeTabby);
        return $product;
    }

    /**
     * Check the condition
     *
     * @param $price
     * @param $type
     * @return int|void
     */
    public function checkCondition($price, $type)
    {
        try {
            $websiteId = $this->getWebsiteId();
            if ($websiteId) {
                if ($type == self::POSTPAY) {
                    $postPayPriceLimit = $this->getPostPayPriceLimit($websiteId);
                    $isPostPayEnabled = $this->isPostPayEnabled($websiteId);
                    if ($isPostPayEnabled == 1 && $price <= $postPayPriceLimit) {
                        return 1;
                    } else {
                        return 0;
                    }
                }
                if ($type == self::TABBY) {
                    $tabbyPriceLimit = $this->getTabbyPriceLimit($websiteId);
                    $isTabbyEnabled = $this->isTabbyEnabled($websiteId);
                    if ($isTabbyEnabled == 1 && $price <= $tabbyPriceLimit) {
                        return 1;
                    } else {
                        return 0;
                    }
                }
            }
        } catch (Exception $e) {
            $this->logger->error(' PDP page error - ' . $e->getMessage());
        }
    }

    /**
     * Get website id
     *
     * @return int|mixed
     */
    public function getWebsiteId()
    {
        try {
            return $this->storeManager->getStore()->getWebsiteId();
        } catch (Exception $e) {
            $this->logger->error(' PDP page error - ' . $e->getMessage());
        }
    }

    /**
     * Get postpay price limit
     *
     * @param int $websiteId
     * @return mixed
     */
    public function getPostPayPriceLimit(int $websiteId)
    {
        return $this->scopeConfig->getValue(
            self::POSTPAY_PRICE_LIMIT,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get tabby price limit
     *
     * @param int $websiteId
     * @return mixed
     */
    public function getTabbyPriceLimit(int $websiteId)
    {
        return $this->scopeConfig->getValue(
            self::TABBY_PRICE_LIMIT,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Check is postpay is enabled
     *
     * @param int $websiteId
     * @return mixed
     */
    public function isPostPayEnabled(int $websiteId)
    {
        return $this->scopeConfig->getValue(
            self::IS_POSTPAY_ENABLED,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Check is tabby is enabled
     *
     * @param int $websiteId
     * @return mixed
     */
    public function isTabbyEnabled(int $websiteId)
    {
        return $this->scopeConfig->getValue(
            self::IS_TABBY_ENABLED,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}
