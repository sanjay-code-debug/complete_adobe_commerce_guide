<?php

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\InitializeCartManagementInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\MaskedQuoteIdToQuoteId;
use Magento\Quote\Api\CartManagementInterface;

class InitializeCartManagement implements InitializeCartManagementInterface
{
    /**
     * @var MaskedQuoteIdToQuoteId
     */
    private MaskedQuoteIdToQuoteId $maskedQuoteIdToQuoteId;
    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @param MaskedQuoteIdToQuoteId $maskedQuoteIdToQuoteId
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        MaskedQuoteIdToQuoteId $maskedQuoteIdToQuoteId,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
        $this->quoteRepository = $quoteRepository;
    }
    /**
     * @inheritdoc
     */
    public function initilizeGuestCart($cartMask)
    {
        try {
            $cartId = $this->maskedQuoteIdToQuoteId->execute($cartMask);
            if ($cartId > 0) {
                return true;
            }
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
    /**
     * @inheritdoc
     */
    public function initilizeLoginCart($customerId)
    {
        try {
            $this->quoteRepository->getActiveForCustomer($customerId);
            return true;
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
