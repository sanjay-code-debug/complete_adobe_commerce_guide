<?php
/**
 * ADOBE CONFIDENTIAL
 * ___________________
 *
 * Copyright 2022 Adobe
 * All Rights Reserved.
 *
 * NOTICE: All information contained herein is, and remains
 * the property of Adobe and its suppliers, if any. The intellectual
 * and technical concepts contained herein are proprietary to Adobe
 * and its suppliers and are protected by all applicable intellectual
 * property laws, including trade secret and copyright laws.
 * Adobe permits you to use and modify this file
 * in accordance with the terms of the Adobe license agreement
 * accompanying it (see LICENSE_ADOBE_PS.txt).
 * If you have received this file from a source other than Adobe,
 * then your use, modification, or distribution of it
 * requires the prior written permission from Adobe.
 */
declare(strict_types=1);

namespace CasioJP\Orico\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Getting order status
 */
class GetOrderStatus
{
    private const CASIOJP_ORICO_ORDER_STATUS = 'casiojp_orico/orico_checkout/orico_order_status';
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get orico order status
     *
     * @param int $websiteId
     * @return mixed
     */
    public function getOrderStatus($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::CASIOJP_ORICO_ORDER_STATUS,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}
