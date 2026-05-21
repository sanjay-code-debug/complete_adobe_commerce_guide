<?php

namespace Codilar\CustomApi\Model\Source;

class CouponSource implements \Magento\Framework\Option\ArrayInterface
{
    public const RULE_IS_FOR_BOTH = '0';
    public const RULE_IS_FOR_WEBSITE = '1';
    public const RULE_IS_FOR_APP = '2';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::RULE_IS_FOR_BOTH,
                'label' => __('Both')
            ],
            [
                'value' => self::RULE_IS_FOR_WEBSITE,
                'label' => __('Website')
            ],
            [
                'value' => self::RULE_IS_FOR_APP,
                'label' => __('APP')
            ]
        ];
    }
}
