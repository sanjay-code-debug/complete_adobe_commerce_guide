<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Plugin;

use Magento\SalesRule\Model\CouponFactory;
use Codilar\CustomApi\Model\Source\CouponSource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\SalesRule\Model\RuleFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\CouponManagement;

class RemoveDiscountCodeFromCartTotal
{
    /**
     * @var CouponFactory
     */
    private CouponFactory $couponFactory;

    /**
     * @var RuleFactory
     */
    private RuleFactory $ruleFactory;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @var CouponManagement
     */
    private CouponManagement $couponManagement;

    /**
     * @param CouponFactory $couponFactory
     * @param RuleFactory $ruleFactory
     * @param CartRepositoryInterface $quoteRepository
     * @param CouponManagement $couponManagement
     */
    public function __construct(
        CouponFactory $couponFactory,
        RuleFactory $ruleFactory,
        CartRepositoryInterface $quoteRepository,
        CouponManagement $couponManagement
    ) {
        $this->couponFactory = $couponFactory;
        $this->ruleFactory = $ruleFactory;
        $this->quoteRepository = $quoteRepository;
        $this->couponManagement = $couponManagement;
    }

    /**
     * @param CartTotalRepositoryInterface $subject
     * @param $cartId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function beforeGet(
        CartTotalRepositoryInterface $subject,
        $cartId
    ) {
        $pattern = '/^\/[^\/]+\/rest\/V1\/carts\/mine\/totals$/';
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        if (preg_match($pattern, $currentUrl)) {
            $quote = $this->quoteRepository->get($cartId);
            $couponCode = $quote->getCouponCode();
            if ($couponCode && !$this->isValidCoupon($couponCode)
            ) {
                try {
                    $this->couponManagement->remove($quote->getId());
                } catch (CouldNotDeleteException | NoSuchEntityException $e) {
                }
            }
        }
        return  $cartId;
    }

    /**
     * Logic to check coupon validity
     *
     * @param $couponCode
     * @return bool
     */
    private function isValidCoupon($couponCode): bool
    {
        $coupon = $this->couponFactory->create();
        $coupon->load($couponCode, 'code');
        $couponId = $coupon->getRuleId();
        $ruleFactory = $this->ruleFactory->create();
        $rule = $ruleFactory->load($couponId);
        $ruleIsUsedFor = $rule->getData('rule_is_for');
        if ($ruleIsUsedFor == CouponSource::RULE_IS_FOR_WEBSITE) {
            return false;
        }
        return true;
    }
}
