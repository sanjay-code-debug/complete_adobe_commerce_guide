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
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\GuestCartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Psr\Log\LoggerInterface as PsrLogger;
use Codilar\CustomApi\Plugin\ModifyPdpPage;
use Codilar\CustomApi\Plugin\CartRepositoryPlugin;
use Codilar\CustomApi\Model\Helper\GetSalableQty;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Codilar\CustomApi\Model\Helper\Data;
use Codilar\CustomApi\Model\Helper\GetProductData;

class CartRepositoryPluginGuest
{
    /**
     * @var PsrLogger
     */
    protected PsrLogger $logger;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var ModifyPdpPage
     */
    protected ModifyPdpPage $modifyPdpPage;

    /**
     * @var CartRepositoryPlugin
     */
    protected CartRepositoryPlugin $cartRepositoryPlugin;

    /**
     * @var GetSalableQty
     */
    private GetSalableQty $getSalableQty;

    /**
     * @var DateTime
     */
    private DateTime $dateTime;
    private Data $data;
    private GetProductData $getProductData;

    /**
     * Constructor
     * @param ProductRepositoryInterface $productRepository
     * @param PsrLogger $logger
     * @param ModifyPdpPage $modifyPdpPage
     * @param CartRepositoryPlugin $cartRepositoryPlugin
     * @param GetSalableQty $getSalableQty
     * @param DateTime $dateTime
     * @param Data $data
     * @param GetProductData $getProductData
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        PsrLogger $logger,
        ModifyPdpPage $modifyPdpPage,
        CartRepositoryPlugin $cartRepositoryPlugin,
        GetSalableQty $getSalableQty,
        DateTime $dateTime,
        Data $data,
        GetProductData $getProductData
    ) {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        $this->modifyPdpPage = $modifyPdpPage;
        $this->cartRepositoryPlugin = $cartRepositoryPlugin;
        $this->getSalableQty = $getSalableQty;
        $this->dateTime = $dateTime;
        $this->data = $data;
        $this->getProductData = $getProductData;
    }

    /**
     * After get
     *
     * @param GuestCartRepositoryInterface $subject
     * @param CartInterface $result
     * @return CartInterface
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function afterGet(GuestCartRepositoryInterface $subject, $result)
    {
        $grandTotal = $result->getGrandTotal();
        $isPostAvailable = $this->getProductData->isPaymentAvailable($grandTotal, GetProductData::POSTPAY);
        $isTabbyAvailable = $this->getProductData->isPaymentAvailable($grandTotal, GetProductData::TABBY);
        $extensionAttributeCart = $result->getExtensionAttributes();
        if ($isPostAvailable['price'] && $isPostAvailable['enable']) {
            $extensionAttributeCart->setIsPostpayAvailable('1');
        } else {
            $extensionAttributeCart->setIsPostpayAvailable('0');
        }

        if ($isTabbyAvailable['price'] && $isTabbyAvailable['enable']) {
            $extensionAttributeCart->setIsTabbyAvailable('1');
        } else {
            $extensionAttributeCart->setIsTabbyAvailable('0');
        }
        $extensionAttributeCart->setIsApplepayEnable($this->getProductData->
        isPaymentAvailable(0, 'applepay')['enable']);
        $extensionAttributeCart->setIsCardpaymentEnble($this->getProductData->
        isPaymentAvailable(0, 'cardpayment')['enable']);
        $result->setExtensionAttributes($extensionAttributeCart);
        foreach ($result->getAllVisibleItems() as $item) {
            $item->setPrice($item->getPriceInclTax());
            $image = $this->getProductData->getPdpDescription($item->getSku(), GetProductData::IMAGE);
            $description = $this->getProductData->getPdpDescription(
                $item->getSku(),
                GetProductData::DESCRIPTION
            );
            $senstiveDescription = ucwords(strtolower($description));
            $specialPrice = $item->getData('product')->getData('special_price');
            $special_from_date = $item->getData('product')->getData('special_from_date');
            $special_to_date = $item->getData('product')->getData('special_to_date');
            $originalPrice = $item->getData('product')->getData('price');
            $price = $this->priceCheck($specialPrice, $originalPrice, $special_from_date, $special_to_date);
            $salableQty = $this->getSalableQty->getSalableQty($item->getSku());
            $productId = $item->getData('product')->getData('entity_id');
            $wpValue = $item->getData()['product']->getData('wp_warning');
            $cartItemWarningMessage = $this->data->getCartItemWarningMessage($wpValue);
            $onlyQtyLeft = $this->data->getQtyLeft($item->getProduct());
            $attributeCodeValue = $this->data->getProductWarningMessage($item->getProduct());
            $message = $this->data->getMessageUsingAttributeIdWithOutDeliveryMessage($attributeCodeValue);
            $extensionAttribute = $item->getExtensionAttributes();
            $extensionAttribute->setCustomProductId($productId);
            $extensionAttribute->setImage($image);
            $extensionAttribute->setShortDescription($senstiveDescription);
            $extensionAttribute->setSalableQty($salableQty);
            $extensionAttribute->setOriginalPrice($price);
            $extensionAttribute->setCartItemWarningMessage(ucwords(strtolower($cartItemWarningMessage)));
            $extensionAttribute->setOnlyQtyLeft($onlyQtyLeft);
            $extensionAttribute->setWpWarningMessage($message);
            $item->setExtensionAttributes($extensionAttribute);
        }
        return $result;
    }

    /**
     * Checking the special price and from-date to to-date
     *
     * @param $specialPrice
     * @param $originalPrice
     * @param $special_from_date
     * @param $special_to_date
     * @return mixed
     * @throws Exception
     */
    public function priceCheck($specialPrice, $originalPrice, $special_from_date, $special_to_date)
    {
        $current_date = $this->dateTime->date();
        // Check if $special_from_date
        if ((!empty($specialPrice))) {
            if (empty($special_from_date) && empty($special_to_date)) {
                return $originalPrice;
            } else {
                // Convert date strings to DateTime objects
                $currentDate = new \DateTime($current_date);
                $specialFromDate = new \DateTime($special_from_date);
                $specialToDate = new \DateTime($special_to_date);
                // Check if currentDate is within the special date range and specialPrice is not empty
                if ($currentDate >= $specialFromDate && $currentDate <= $specialToDate) {
                    return $originalPrice;
                }
            }
        }
        return 0;
    }
}
