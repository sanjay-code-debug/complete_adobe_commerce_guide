<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Plugin;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\QuoteRepository;

class PlaceOrderSetExtensionValue
{

    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * PlaceOrderSetExtensionValue constructor.
     *
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Places an order for a specified cart.
     *
     * @param int $cartId The cart ID.
     * @param PaymentInterface|null $paymentMethod
     * @return array Order ID.
     * @throws NoSuchEntityException
     */
    public function beforePlaceOrder(CartManagementInterface $subject, $cartId, $paymentMethod = null)
    {
        if ($paymentMethod != null) {
            // get the version and model type
            $appVersion = $paymentMethod->getExtensionAttributes()->getAppVersion();
            $iphoneModel = $paymentMethod->getExtensionAttributes()->getIphoneModelType();

            // Check if values are present
            if ($appVersion === null || $iphoneModel === null) {
                return [$cartId, $paymentMethod];
            }

            // Set values in the quote table
            $quote = $this->quoteRepository->getActive($cartId);
            $quote->setData('app_version', $appVersion);
            $quote->setData('iphone_model_type', $iphoneModel);
            $this->quoteRepository->save($quote);
            return [$cartId, $paymentMethod];
        }
        return [$cartId, $paymentMethod];
    }
}
