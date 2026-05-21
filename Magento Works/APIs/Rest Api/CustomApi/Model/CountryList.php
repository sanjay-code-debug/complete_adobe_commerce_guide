<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\CountryListInterface;
use Magento\Store\Model\ResourceModel\Website\CollectionFactory as WebsiteCollectionFactory;
use Exception;
use Psr\Log\LoggerInterface as PsrLogger;

class CountryList implements CountryListInterface
{
    /**
     * @var WebsiteCollectionFactory
     */
    protected WebsiteCollectionFactory $country;

    /**
     * @var PsrLogger
     */
    protected PsrLogger $logger;

    /**
     * @param WebsiteCollectionFactory $country
     * @param PsrLogger $logger
     */
    public function __construct(
        WebsiteCollectionFactory $country,
        PsrLogger $logger
    ) {
        $this->country = $country;
        $this->logger = $logger;
    }

    /**
     * Get country list
     *
     * @return array
     */
    public function getCountryList(): array
    {
        try {
            $websites  = $this->country->create();
            $country = [];
            foreach ($websites as $website) {
                if ($website->getName() === "UAE") {
                    $country[] = [
                        "name " => $website->getName(),
                        "base_url" => $website->getDefaultStore()->getBaseUrl(),
                        "currency" => $website->getDefaultStore()->getCurrentCurrencyCode(),
                        "store_id" => $website->getDefaultStore()->getId(),
                        "store_code" => $website->getCode(),
                        "store_name" => $website->getName(),
                        "store_view" => $website->getDefaultStore()->getName()
                    ];
                }
            }
            return $country;
        } catch (Exception $e) {
            $this->logger->error('Country list error - ' . $e->getMessage());
            return ['error' => true, 'message' => 'something went wrong'];
        }
    }
}
