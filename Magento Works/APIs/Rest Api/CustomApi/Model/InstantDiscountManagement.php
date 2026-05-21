<?php

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\InstantDiscountManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use RedChamps\InstantCartDiscount\Api\DiscountDataRepositoryInterface;
use RedChamps\InstantCartDiscount\Model\DiscountManager;
use Psr\Log\LoggerInterface as PsrLogger;
use Magento\Checkout\Model\Session;

class InstantDiscountManagement implements InstantDiscountManagementInterface
{

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @var DiscountManager
     */
    private DiscountManager $discountManager;

    /**
     * @var DiscountDataRepositoryInterface
     */
    private DiscountDataRepositoryInterface $discountDataRepository;

    /**
     * @var PsrLogger
     */
    private PsrLogger $logger;
    /**
     * @var Session
     */
    private Session $session;

    /***
     * @param CartRepositoryInterface $quoteRepository
     * @param DiscountManager $discountManager
     * @param DiscountDataRepositoryInterface $discountDataRepository
     * @param PsrLogger $logger
     * @param Session $session
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        DiscountManager $discountManager,
        DiscountDataRepositoryInterface $discountDataRepository,
        PsrLogger $logger,
        Session $session
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->discountManager = $discountManager;
        $this->discountDataRepository = $discountDataRepository;
        $this->logger = $logger;
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */

    public function removeInstantDiscountLogin($cartId)
    {
        return $this->removeInstantDiscount($cartId);
    }
    /**
     * @inheritdoc
     */

    public function guestRemoveInstantDiscount($cartId)
    {
        return $this->removeInstantDiscount($cartId);
    }

    /**
     * @inheritdoc
     */
    public function removeInstantDiscount($cartId)
    {
        try {
            $this->session->setInstantCouponRemove(true);
            $quote = $this->quoteRepository->getActive($cartId);
        } catch (NoSuchEntityException $e) {
            $this->logger->info($e->getMessage());
            $this->session->unsInstantCouponRemove();
            return false;
        }
        $rules = $quote->getAppliedRuleIds();
        $instantDiscountRuleId = (int)$this->discountManager->getRuleId();
        try {
            $discountDataEntity = $this->discountDataRepository->getByQuoteId($quote->getId());
        } catch (LocalizedException $e) {
            $this->logger->info($e->getMessage());
            $this->session->unsInstantCouponRemove();
            return false;
        }
        $ruleIds = explode(",", $rules);
        if (is_array($ruleIds)) {
            try {
                $isCouponRemoved = $this->discountDataRepository->deleteById($discountDataEntity->getId());
                if ($isCouponRemoved && in_array($instantDiscountRuleId, $ruleIds)) {
                    $filterRuleIds = $this->removeInstantDiscountFromRules($instantDiscountRuleId, $ruleIds);
                    $quote->setAppliedRuleIds($filterRuleIds);
                    $quote->getShippingAddress()->setCollectShippingRates(true);
                    $quote->collectTotals();
                    $this->quoteRepository->save($quote);
                    $this->session->unsInstantCouponRemove();
                    return true;
                }
            } catch (NoSuchEntityException | LocalizedException $e) {
                $this->logger->info($e->getMessage());
                $this->session->unsInstantCouponRemove();
                return false;
            }
        }
        return false;
    }

    /**
     * Remove the instant discount from applied rule
     *
     * @param int $instantDiscountRuleId
     * @param array $ruleIds
     * @return string
     */
    private function removeInstantDiscountFromRules($instantDiscountRuleId, $ruleIds)
    {
        if (count($ruleIds) > 1) {
            $removeInstantDiscountRules = array_filter($ruleIds, function ($ruleId) use ($instantDiscountRuleId) {
                return $ruleId != $instantDiscountRuleId;
            });
            return implode(",", $removeInstantDiscountRules);
        }
        return '';
    }
}
