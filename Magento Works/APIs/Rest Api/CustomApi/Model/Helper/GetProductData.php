<?php

namespace Codilar\CustomApi\Model\Helper;

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface as PsrLogger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class GetProductData
{
    /**
     * Declare const
     */
    public const IMAGE = 'image';

    /**
     * Declare const
     */
    public const DESCRIPTION = 'description';

    /**
     * Declare const
     */
    public const POSTPAY = 'postpay';

    /**
     * Declare const
     */
    public const TABBY = 'tabby';
    /**
     * Declare const
     */
    public const APPLEPAY = 'applepay';

    /**
     * Declare const
     */
    public const CARDPAYMENT = 'cardpayment';


    /**
     * Get config value for postpay
     */
    public const POSTPAY_PRICE_LIMIT = 'footer_config/bnpl/postpay_price_limit';

    /**
     * Get config value for tabby
     */
    public const TABBY_PRICE_LIMIT = 'footer_config/bnpl/tabby_price_limit';

    /**
     * Check the postpay is active
     */
    public const IS_POSTPAY_ENABLED = 'payment/postpay_split_payment/enable';

    /**
     * Check the tabby is active
     */
    public const IS_TABBY_ENABLED = 'payment/tabby_installments/enable';
    
    public const IS_CARD_PAYMENT_ENABLED = 'payment/checkoutcom_card_payment/enable';

    public const IS_APPLEPAY_ENABLED = 'payment/checkoutcom_apple_pay/active';

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var PsrLogger
     */
    private PsrLogger $logger;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;
    private StoreManagerInterface $storeManager;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param PsrLogger $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        PsrLogger $logger,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $sku
     * @param $type
     * @return string
     * @throws NoSuchEntityException
     */
    public function getPdpDescription($sku, $type)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $product = $this->productRepository->get($sku, false, $storeId);

        if ($type == self::IMAGE) {
            $image_url = $product->getThumbnail();
            if ($image_url) {
                return $image_url;
            } else {
                return null;
            }
        } else {
            if ($type == self::DESCRIPTION) {
                $attributeNames = ['material_f', 'capacity', 'size', 'shape', 'color_f'];
                $descriptionParts = [];

                foreach ($attributeNames as $attributeName) {
                    $attribute = $product->getCustomAttribute($attributeName);
                    $attributeValue = $attribute ? $attribute->getValue() : null;
                    $attributeOptions = $product->getResource()->getAttribute($attributeName)->getOptions();
                    $attributeValueName = $this->getOptionName($attributeOptions, $attributeValue);

                    if (!empty($attributeValueName) && $attributeValueName !== " ") {
                        $descriptionParts[] = $attributeValueName;
                    }
                }

                if (!empty($descriptionParts)) {
                    return implode(' | ', $descriptionParts);
                } else {
                    return "No description available.";
                }
            }
        }
    }

    /**
     * Get option name
     *
     * @param $options
     * @param $capacity
     * @return mixed
     */
    public function getOptionName($options, $capacity)
    {
        try {
            $name = null;
            foreach ($options as $option) {
                if ($option->getValue() == $capacity) {
                    $name = $option->getLabel();
                    break;
                }
            }
            return $name;
        } catch (Exception $e) {
            $this->logger->error('When Fetch Option getting the error  - ' . $e->getMessage());
        }
    }

    /**
     * Payment method available check
     *
     * @param $type
     * @param $price
     * @return array
     */
    public function isPaymentAvailable($price, $type)
    {
        try {
            $websiteId = $this->getWebsiteId();
            switch ($type) {
                case self::POSTPAY:
                    $postPayPriceLimit = $this->scopeConfig->getValue(
                        self::POSTPAY_PRICE_LIMIT,
                        ScopeInterface::SCOPE_WEBSITE,
                        $websiteId
                    );
                    $isPostPayEnabled = $this->scopeConfig->getValue(
                        self::IS_POSTPAY_ENABLED,
                        ScopeInterface::SCOPE_WEBSITE,
                        $websiteId
                    );
                    return [
                        'enable' => $isPostPayEnabled,
                        'price' => $price <= $postPayPriceLimit
                    ];
                case self::TABBY:
                    $tabbyPriceLimit = $this->scopeConfig->getValue(
                        self::TABBY_PRICE_LIMIT,
                        ScopeInterface::SCOPE_WEBSITE,
                        $websiteId
                    );
                    $isTabbyEnabled = $this->scopeConfig->getValue(
                        self::IS_TABBY_ENABLED,
                        ScopeInterface::SCOPE_WEBSITE,
                        $websiteId
                    );
                    return [
                        'enable' => $isTabbyEnabled,
                        'price' => $price <= $tabbyPriceLimit
                    ];
                case self::APPLEPAY:
                    return [
                        'enable' => $this->scopeConfig->getValue(
                            self::IS_APPLEPAY_ENABLED,
                            ScopeInterface::SCOPE_WEBSITE,
                            $websiteId
                        ),
                        'price' => true
                    ];
                case self::CARDPAYMENT:
                    return [
                        'enable' => $this->scopeConfig->getValue(
                            self::IS_CARD_PAYMENT_ENABLED,
                            ScopeInterface::SCOPE_WEBSITE,
                            $websiteId
                        ),
                        'price' => true
                    ];
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
}
