<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Plugin;

use Codilar\CustomApi\Model\Helper\GetProductData;
use Exception;
use Magento\Checkout\Api\ShippingInformationManagementInterface;
use  Magento\Checkout\Api\Data\PaymentDetailsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Codilar\HomePageManager\Logger\Logger;

class CheckoutPaymentMethod
{
    /**
     * Tabby
     */
    public const TABBY = 'tabby_installments';

    /**
     * PostPay
     */
    public const POST_PAY = 'postpay';

    /**
     * ApplePay
     */
    public const APPLE_PAY = 'checkoutcom_apple_pay';

    /**
     * Checkoutcom card payment
     */
    public const CHECKOUTCOM_CARD_PAYMENT = 'checkoutcom_card_payment';

    /**
     * Checkoutcom save card payment
     */
    public const CHECKOUTCOM_SAVE_CARD_PAYMENT = 'checkoutcom_vault';

    /**
     * Config path
     */
    public const POSTPAY_PRICE_LIMIT = 'footer_config/bnpl/postpay_price_limit';

    /**
     * Config path
     */
    public const TABBY_PRICE_LIMIT = 'footer_config/bnpl/tabby_price_limit';

    /**
     * Config path
     */
    public const ENABLE_TABBY_FOR_APP = 'payment/tabby_installments/enable';

    /**
     * Config path
     */
    public const ENABLE_POSTPAY_FOR_APP = 'payment/postpay_split_payment/enable';

    /**
     * Config path
     */
    public const ENABLE_APPLE_PAY_FOR_APP = 'payment/checkoutcom_apple_pay/enable';

    /**
     * Config path
     */
    public const ENABLE_CHECKOUTCOM_CARD_PAYMENT_FOR_APP = 'payment/checkoutcom_card_payment/enable';

    /**
     * Config path
     */
    public const ENABLE_CHECKOUTCOM_SAVE_CARD_FOR_APP = 'payment/checkoutcom_vault/enable';


    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Logger
     */
    private $logger;
    private GetProductData $getProductData;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Logger $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Logger $logger,
        GetProductData $getProductData
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->getProductData = $getProductData;
    }

    /**
     * @param ShippingInformationManagementInterface $subject
     * @param PaymentDetailsInterface $result
     * @param $cartId
     * @param $addressInformation
     * @return PaymentDetailsInterface
     * @throws LocalizedException
     */
    public function afterSaveAddressInformation(
        ShippingInformationManagementInterface $subject,
        $result,
        $cartId,
        $addressInformation
    ) {
        try {
            $websiteId = $this->getWebsiteId();
            $postPayPriceLimit = $this->scopeConfig->getValue(
                self::POSTPAY_PRICE_LIMIT,
                ScopeInterface::SCOPE_WEBSITE,
                $websiteId
            );
            $tabbyPriceLimit = $this->scopeConfig->getValue(
                self::TABBY_PRICE_LIMIT,
                ScopeInterface::SCOPE_WEBSITE,
                $websiteId
            );

            // holding value from api to identify the payment method call is from - APP or Website
            $paymentMethodForIos = $addressInformation->getExtensionAttributes()->getPaymentMethodForApp();
            $methods = $result->getData('payment_methods');
            $cartTotal = $result->getData('totals')->getData('total_segments')['grand_total']->getData('value');
            $removeMethods = [];

            $postPayAvailable = $this->getProductData->isPaymentAvailable(0, 'postpay');
            $tabbyAvailable = $this->getProductData->isPaymentAvailable(0, 'tabby');
            $extensionAttributeCart = $result->getExtensionAttributes();
            $extensionAttributeCart->setIsTabbyEnable($tabbyAvailable['enable']);
            $extensionAttributeCart->setIsPostpayEnable($postPayAvailable['enable']);
            $extensionAttributeCart->setIsApplepayEnable($this->getProductData->
            isPaymentAvailable(0, 'applepay')['enable']);
            $extensionAttributeCart->setIsCardpaymentEnble($this->getProductData->
            isPaymentAvailable(0, 'cardpayment')['enable']);
            $result->setExtensionAttributes($extensionAttributeCart);

            // Define a mapping array of method codes and their configuration paths for enablement
            $methodsToCheck = [
                self::TABBY => self::ENABLE_TABBY_FOR_APP,
                self::POST_PAY => self::ENABLE_POSTPAY_FOR_APP,
                self::APPLE_PAY => self::ENABLE_APPLE_PAY_FOR_APP,
                self::CHECKOUTCOM_CARD_PAYMENT => self::ENABLE_CHECKOUTCOM_CARD_PAYMENT_FOR_APP,
                self::CHECKOUTCOM_SAVE_CARD_PAYMENT => self::ENABLE_CHECKOUTCOM_SAVE_CARD_FOR_APP,
            ];

            if ($paymentMethodForIos) {
                foreach ($methods as $key => $method) {
                    $methodCode = $method->getCode();
                    // Check if the method code exists in the mapping array and perform the enablement check
                    if (array_key_exists($methodCode, $methodsToCheck)) {
                        $configPath = $methodsToCheck[$methodCode];
                        $isEnabled = $this->scopeConfig->getValue(
                            $configPath,
                            ScopeInterface::SCOPE_WEBSITE,
                            $websiteId
                        );
                        // Check if the method is not enabled and remove it from the methods array
                        if (!$isEnabled) {
                            unset($methods[$key]);
                            $removeMethods[] = $key;
                        }
                    }
                    if (
                        ($methodCode === self::POST_PAY && $cartTotal > $postPayPriceLimit) ||
                        ($methodCode === self::TABBY && $cartTotal > $tabbyPriceLimit)
                    ) {
                        unset($methods[$key]);
                        $removeMethods[] = $key;
                    }
                }
                if (!empty($removeMethods)) {
                    $result->setData('payment_methods', $methods);
                } else {
                    return $result;
                }
            } else {
                foreach ($methods as $key => $method) {
                    $methodCode = $method->getCode();
                    if (
                        ($methodCode === self::POST_PAY && $cartTotal > $postPayPriceLimit) ||
                        ($methodCode === self::TABBY && $cartTotal > $tabbyPriceLimit)
                    ) {
                        unset($methods[$key]);
                        $removeMethods[] = $key;
                    }
                }
                if (!empty($removeMethods)) {
                    $result->setData('payment_methods', $methods);
                } else {
                    return $result;
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('some issue with checkout payment methods for tabby and post pay'));
        }
        return $result;
    }

    /**
     * @return int
     * @throws LocalizedException
     */
    public function getWebsiteId()
    {
        try {
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
        } catch (Exception $e) {
            $this->logger->error(' Checkout payment method errors when getting website id- ' . $e->getMessage());
            throw new LocalizedException(__('not getting the website id'));
        }
        return $websiteId;
    }
}
