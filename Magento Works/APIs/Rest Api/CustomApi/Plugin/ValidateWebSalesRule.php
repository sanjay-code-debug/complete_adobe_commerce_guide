<?php

namespace Codilar\CustomApi\Plugin;

use Codilar\CustomApi\Model\Source\CouponSource;
use Magento\SalesRule\Model\Utility;

class ValidateWebSalesRule
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
//        if ($ruleIsUsedFor == CouponSource::RULE_IS_FOR_APP) {
//            $result = false;
//        }
        return $result;
    }
}
