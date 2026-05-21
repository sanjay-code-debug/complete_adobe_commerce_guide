<?php

namespace Codilar\CustomApi\Plugin;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Quote\Model\GuestCart\GuestCouponManagement;
use Magento\SalesRule\Model\CouponFactory;
use Magento\SalesRule\Model\RuleFactory;
use Codilar\CustomApi\Model\Source\CouponSource;

class AroundGuestCouponManagement
{
    /**
     * @var RuleFactory
     */
    protected $ruleFactory;
    /**
     * @var CouponFactory
     */
    protected $couponFactory;

    /**
     * @param RuleFactory $ruleFactory
     * @param CouponFactory $couponFactory
     */
    public function __construct(
        RuleFactory $ruleFactory,
        CouponFactory $couponFactory
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->couponFactory = $couponFactory;
    }

    /**
     * @param GuestCouponManagement $subject
     * @param callable $proceed
     * @param $cartId
     * @param $couponCode
     * @return mixed
     * @throws CouldNotSaveException
     */
    public function aroundSet(GuestCouponManagement $subject, callable $proceed, $cartId, $couponCode)
    {
        $coupon = $this->couponFactory->create();
        $coupon->load($couponCode, 'code');
        $couponId = $coupon->getRuleId();
        $ruleFactory = $this->ruleFactory->create();
        $rule = $ruleFactory->load($couponId);
        $ruleIsUsedFor = $rule->getData('rule_is_for');
        if ($ruleIsUsedFor == CouponSource::RULE_IS_FOR_WEBSITE) {
            throw new CouldNotSaveException(__('This coupon is a web exclusive. To redeem it, please head over to our website.'));
        }
        return $proceed($cartId, $couponCode);
    }
}

