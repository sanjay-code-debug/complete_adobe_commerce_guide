<?php

namespace Codilar\CustomApi\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\Data\TotalsInterface as QuoteTotalsInterface;
use RedChamps\InstantCartDiscount\Api\DiscountDataRepositoryInterface;
use RedChamps\InstantCartDiscount\Model\DiscountManager;
use Magento\Framework\Exception\LocalizedException;

class CartTotalPlugin
{
    /**
     * @var DiscountManager
     */
    private DiscountManager $discountManager;
    /**
     * @var DiscountDataRepositoryInterface
     */
    private DiscountDataRepositoryInterface $discountDataRepository;
    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @param DiscountManager $discountManager
     * @param DiscountDataRepositoryInterface $discountDataRepository
     * @param CartRepositoryInterface $quoteRepository
     */

    public function __construct(
        DiscountManager $discountManager,
        DiscountDataRepositoryInterface $discountDataRepository,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->discountManager = $discountManager;
        $this->discountDataRepository = $discountDataRepository;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * After get
     *
     * @param CartTotalRepositoryInterface $repository
     * @param QuoteTotalsInterface $result
     * @param int $cartId
     * @return QuoteTotalsInterface
     */
    public function afterGet(CartTotalRepositoryInterface $repository, $result, $cartId)
    {
        if ($cartId) {
            $quote = $this->quoteRepository->getActive($cartId);
            $rules = $quote->getAppliedRuleIds();
            $ruleIds = explode(",", $rules);
            $instantDiscountRuleId = (int)$this->discountManager->getRuleId();
            if (in_array($instantDiscountRuleId, $ruleIds)) {
                try {
                    $this->discountDataRepository->getByQuoteId($quote->getId());
                    $result->getExtensionAttributes()->setInstantCouponApplied(true);
                } catch (LocalizedException $e) {
                    $result->getExtensionAttributes()->setInstantCouponApplied(false);
                }
            } else {
                $result->getExtensionAttributes()->setInstantCouponApplied(false);
            }
        }
        return $result;
    }
}
