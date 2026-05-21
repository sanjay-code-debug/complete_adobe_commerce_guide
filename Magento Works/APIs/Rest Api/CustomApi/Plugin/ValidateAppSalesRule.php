<?php

namespace Codilar\CustomApi\Plugin;

use Codilar\CustomApi\Model\Source\CouponSource;
use Magento\SalesRule\Model\Utility;

class ValidateAppSalesRule
{
    /**
     * @param Utility $subject
     * @param bool $result
     * @param Rule $rule
     * @param Address $address
     * @return bool
     */
    public function afterCanProcessRule(Utility $subject, bool $result, $rule, $address): bool
    {
//        $ruleIsUsedFor = $rule->getData('rule_is_for');
//        if(is_array($_SERVER) && array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
//            $userAgent = $_SERVER['HTTP_USER_AGENT'];
//            if (preg_match('/Mozilla\/|Chrome\/|Safari\/|Edge\//', $userAgent) && $ruleIsUsedFor == CouponSource::RULE_IS_FOR_APP) {
//                $result = false;
//            }
//        }
//        if ($ruleIsUsedFor == CouponSource::RULE_IS_FOR_WEBSITE) {
//            $result = false;
//        }
        return $result;
    }
}
