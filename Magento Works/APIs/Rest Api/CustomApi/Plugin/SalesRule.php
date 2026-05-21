<?php

namespace Codilar\CustomApi\Plugin;

use Codilar\CustomApi\Model\Source\CouponSource;
use Magento\Quote\Model\Quote\Item;
use Magento\SalesRule\Model\CouponFactory;
use Magento\SalesRule\Model\ResourceModel\Rule as RuleResourceModel;
use Magento\SalesRule\Model\RuleFactory;
use Magento\SalesRule\Model\RulesApplier;
use Psr\Log\LoggerInterface;
use Codilar\Checkout\Plugin\SalesRule as WebSalesRule;

class SalesRule
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
     * @var RuleResourceModel
     */
    private RuleResourceModel $ruleResuorceModel;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var WebSalesRule
     */
    private WebSalesRule $webSalesRule;

    /**
     * @param CouponFactory $couponFactory
     * @param RuleResourceModel $ruleResourceModel
     * @param RuleFactory $ruleFactory
     * @param LoggerInterface $logger
     * @param WebSalesRule $webSalesRule
     */
    public function __construct(
        CouponFactory $couponFactory,
        RuleResourceModel $ruleResourceModel,
        RuleFactory $ruleFactory,
        LoggerInterface $logger,
        WebSalesRule $webSalesRule
    ) {
        $this->couponFactory = $couponFactory;
        $this->ruleFactory = $ruleFactory;
        $this->ruleResuorceModel = $ruleResourceModel;
        $this->logger = $logger;
        $this->webSalesRule = $webSalesRule;
    }

    /**
     * Apply rule before validation  for app
     *
     * @param RulesApplier $rulesApplier
     * @param $item
     * @param $rules
     * @param $skipValidation
     * @param $couponCode
     * @return array
     */
    public function beforeApplyRules(RulesApplier $rulesApplier, $item, $rules, $skipValidation, $couponCode)
    {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';

        //Considering this API as website called because this API called at checkout
        if (strpos($currentUrl, "V1/carts/mine/estimate-shipping-methods-by-address-id") !== false) {
            return $this->webSalesRule->beforeApplyRules($rulesApplier, $item, $rules, $skipValidation, $couponCode);
        }
        //Considering this API as website called because this API called at checkout
        if (strpos($currentUrl, "V1/carts/mine/totals-information") !== false) {
            return $this->webSalesRule->beforeApplyRules($rulesApplier, $item, $rules, $skipValidation, $couponCode);
        }

        // Split the URL by '/'
        $urlParts = explode('/', $currentUrl);

        // Find the index of 'V1' and get the substring from that point
        $startIndex = array_search('V1', $urlParts);
        $output = implode('/', array_slice($urlParts, $startIndex));

        if ($output !== "V1/guest-carts/estimate-shipping-methods" && isset($_SERVER['HTTP_REFERER'])) {
            return $this->webSalesRule->beforeApplyRules($rulesApplier, $item, $rules, $skipValidation, $couponCode);
        }

        if ($couponCode !== null) {
            $couponCode = $this->validateAndRemoveCoupon($couponCode, $item->getQuote());
        }
        $appRules = [];
        foreach ($rules as $rule) {
            $ruleIsUsedFor = $rule->getData('rule_is_for');
            if ($ruleIsUsedFor != CouponSource::RULE_IS_FOR_WEBSITE) {
                $appRules[$rule->getRuleId()] = $rule;
            }
        }
        $actualRulesForApp = $this->removeAlreadyAppliedRuleForApp($item, $appRules);
        return[$item, $actualRulesForApp, $skipValidation, $couponCode];
    }

    /**
     * Validate and remove coupon code
     *
     * @param $couponCode
     * @param $quote
     * @return mixed|null
     */
    private function validateAndRemoveCoupon($couponCode, $quote)
    {
        if (!empty($quote) && !empty($quote->getCouponCode())) {
            $coupon = $this->couponFactory->create();
            $coupon = $coupon->loadByCode($couponCode);
            $ruleId = $coupon->getRuleId();
            try {
                $rule = $this->ruleFactory->create();
                $this->ruleResuorceModel->load($rule, $ruleId, "rule_id");
            } catch (\Exception  $e) {
                $rule = null;
            }
            if (!empty($rule)) {
                $ruleIsUsedFor = $rule->getData("rule_is_for");
                if ($ruleIsUsedFor == CouponSource::RULE_IS_FOR_WEBSITE) {
                    try {
                        $quote->setCouponCode(null); // set null to coupon code
                        $quote->collectTotals()->save(); //saved quote
                    } catch (\Exception $exception) {
                        $this->logger->info($exception->getMessage());
                        return $couponCode;
                    }
                    return null;
                }
            }
        }
        return $couponCode;
    }

    /**
     * Validate and remove auto applied cart rule
     *
     * @param Item $item
     * @param array $appRules
     * @return array|mixed
     */
    private function removeAlreadyAppliedRuleForApp($item, $appRules)
    {
        $actualAppRules = [];
        $appRulesIds = array_keys($appRules);
        $appliedRuleIds = $item->getAppliedRuleIds();
        if (!empty($appliedRuleIds)) {
            if (is_string($appliedRuleIds)) {
                $appliedRuleIds = explode(',', $appliedRuleIds);
            }
            $actualRulesIds = [];
            foreach ($appliedRuleIds as $ruleId) {
                if (in_array($ruleId, $appRulesIds)) {
                    $actualRulesIds[] = $ruleId;
                }
            }
            if (count(array_diff($appliedRuleIds, $appRulesIds)) > 0) {
                foreach ($actualRulesIds as $rulesId) {
                    if (isset($appRules[$rulesId])) {
                        $actualAppRules[] = $appRules[$rulesId];
                    }
                }
            }

            if (count($actualAppRules) > 0) {
                return $actualAppRules;
            }
            return $appRules;
        }
        return $appRules;
    }
}
