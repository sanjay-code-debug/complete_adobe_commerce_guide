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

namespace CasioJP\Orico\Block;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Orico data helper
 */
class Data implements ConfigProviderInterface
{
    private const XML_ORICO_CHECKOUT_DESCRIPTION = 'casiojp_orico/orico_checkout/orico_description';

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
     * Get orico checkout description
     *
     * @param int $websiteId
     * @return mixed
     */
    public function getOricoCheckoutDescription($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_ORICO_CHECKOUT_DESCRIPTION,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get Config
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        $config['myCustomData'] = $this->getOricoCheckoutDescription();
        return $config;
    }
}
