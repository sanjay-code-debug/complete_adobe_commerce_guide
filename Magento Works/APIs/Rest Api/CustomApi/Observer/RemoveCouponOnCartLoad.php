<?php

namespace Codilar\CustomApi\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codilar\CustomApi\Model\Source\CouponSource;
use Magento\SalesRule\Model\CouponFactory;
use Magento\SalesRule\Model\RuleFactory;
use Psr\Log\LoggerInterface as PsrLogger;

class RemoveCouponOnCartLoad implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    protected CheckoutSession $checkoutSession;

    /**
     * @var CouponFactory
     */
    private CouponFactory $couponFactory;

    /**
     * @var RuleFactory
     */
    private RuleFactory $ruleFactory;

    /**
     * @var PsrLogger
     */
    private PsrLogger $logger;

    /**
     * @param CheckoutSession $checkoutSession
     * @param CouponFactory $couponFactory
     * @param RuleFactory $ruleFactory
     * @param PsrLogger $logger
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        CouponFactory $couponFactory,
        RuleFactory $ruleFactory,
        PsrLogger $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->couponFactory = $couponFactory;
        $this->ruleFactory = $ruleFactory;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $quote = $this->checkoutSession->getQuote();
        $couponCode = $quote->getCouponCode();
        if ($couponCode && !$this->isValidCoupon($couponCode)) {
                $quote->setCouponCode('')->collectTotals()->save();
        }
        $ruleId = $quote->getData('applied_rule_ids');
        $ruleIds = [];
        if (!empty($ruleId)) {
            $ruleIds = explode(',', $ruleId);
        }
        if (count($ruleIds) > 0 && !$this->removeAutoCoupon($ruleIds)) {
            $items = $quote->getData('items');
            if (!empty($items)) {
                //setting the  applied rule ids as null bcz to remove app coupon after this event.
                //sales rule will get call & there website rule will be added from the cart
                foreach ($items as $item) {
                    $item->setData('applied_rule_ids', null);
                }
            }
            $quote->collectTotals()->save();
        }
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
        if ($ruleIsUsedFor == CouponSource::RULE_IS_FOR_APP) {
            return false;
        }
        return true;
    }

    /**
     * Checking the auto coupon code for app or website
     *
     * @param $ruleIds
     * @return bool
     */
    public function removeAutoCoupon($ruleIds)
    {
        foreach ($ruleIds as $ruleId) {
            $ruleFactory = $this->ruleFactory->create();
            $rule = $ruleFactory->load($ruleId);
            $ruleIsUsedFor = $rule->getData('rule_is_for');
            if ($ruleIsUsedFor == CouponSource::RULE_IS_FOR_APP) {
                return false;
            }
        }
        return true;
    }
}
