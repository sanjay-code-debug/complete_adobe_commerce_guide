<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\PopularSearchInterface;
use Magento\Framework\HTTP\Client\Curl;
use Exception;
use Codilar\CustomApi\Api\Data\Message\PopularSearchResultInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface as PsrLogger;

class PopularSearch implements PopularSearchInterface
{
    /**
     * @var Curl
     */
    private Curl $curl;
    /**
     * @var PopularSearchResultInterface
     */
    private PopularSearchResultInterface $popularSearchResult;

    /**
     * get url key
     */
    public const KLEVU_URL = "footer_config/klevu_url/url";

    /**
     * get kelvu id
     */
    public const KLEVU_ID = "klevu_search/general/js_api_key";

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var PsrLogger
     */
    private PsrLogger $logger;

    /**
     * @param Curl $curl
     * @param PopularSearchResultInterface $popularSearchResult
     * @param ScopeConfigInterface $scopeConfig
     * @param PsrLogger $logger
     */
    public function __construct(
        Curl $curl,
        PopularSearchResultInterface $popularSearchResult,
        ScopeConfigInterface $scopeConfig,
        PsrLogger $logger
    ) {
        $this->curl = $curl;
        $this->popularSearchResult = $popularSearchResult;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function getPopularSearches(): PopularSearchResultInterface
    {
        $klevuUrl = $this->scopeConfig->getValue(
            self::KLEVU_URL,
            ScopeInterface::SCOPE_STORE
        );

        $klevuId =  $this->scopeConfig->getValue(
            self::KLEVU_ID,
            ScopeInterface::SCOPE_STORE
        );
        $url = $klevuUrl . $klevuId . '.json';

        try {
            $this->curl->get($url);
            $response = $this->curl->getBody();
            $jsonData = json_decode($response, true);
            // Check if the key exists in the response
            if (isset($jsonData['klevu_webstorePopularTerms']) && is_array($jsonData['klevu_webstorePopularTerms'])) {
                $this->popularSearchResult->setPopularSearches($jsonData['klevu_webstorePopularTerms']);
            }
        } catch (Exception $e) {
            $this->logger->error('klevu popular search error - ' . $e->getMessage());
        }
        return $this->popularSearchResult;
    }
}
