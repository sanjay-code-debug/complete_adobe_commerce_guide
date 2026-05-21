<?php

namespace Codilar\CustomApi\Model\InstantCartDiscount;

use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartInterface;
use Psr\Log\LoggerInterface;
use RedChamps\InstantCartDiscount\Api\DiscountDataRepositoryInterface;
use RedChamps\InstantCartDiscount\Plugin\Cart\GetAttributes as RedChampsGetAttributes;

class GetAttributes extends RedChampsGetAttributes
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @param DiscountDataRepositoryInterface $discountDataRepository
     * @param LoggerInterface $logger
     * @param CartExtensionFactory $cartExtensionFactory
     * @param Session $session
     */
    public function __construct(
        DiscountDataRepositoryInterface $discountDataRepository,
        LoggerInterface $logger,
        CartExtensionFactory            $cartExtensionFactory,
        Session $session
    ) {
        parent::__construct($discountDataRepository, $logger, $cartExtensionFactory);
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function afterGet(
        CartRepositoryInterface $subject,
        CartInterface $resultQuote
    ) {
        return $this->addDiscountDataToQuote($resultQuote);
    }

    /**
     * Add instant Discount to cart
     *
     * @param CartInterface $quote
     * @return CartInterface
     */
    private function addDiscountDataToQuote(CartInterface $quote)
    {
        $isInstantCouponRemove = $this->session->getInstantCouponRemove();
        if ($isInstantCouponRemove) {
            return $quote;
        }
        $extensionAttributes = $quote->getExtensionAttributes();
        $cartExtension = $extensionAttributes ? $extensionAttributes : $this->cartExtensionFactory->create();
        if (!$cartExtension->getInstantDiscountData()) {
            try {
                $attributeValue = $this->discountDataRepository->getByQuoteId($quote->getEntityId());
            } catch (NoSuchEntityException $e) {
                return $quote;
            }
            $cartExtension->setInstantDiscountData($attributeValue);
            $quote->setExtensionAttributes($cartExtension);
        }
        return $quote;
    }
}
