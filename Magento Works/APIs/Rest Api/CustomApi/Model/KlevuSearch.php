<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */
namespace Codilar\CustomApi\Model;

use Klevu\Categorynavigation\Model\Api\Action\CatnavIdsearch as KlevuCatnavApiIdsearch;
use Klevu\Search\Helper\Config as KlevuHelperConfig;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface as PsrLogger;

class KlevuSearch
{

    /**
     * @var PsrLogger
     */
    protected PsrLogger $logger;

    protected KlevuHelperConfig $searchHelperConfig;

    protected KlevuCatnavApiIdsearch $apiActionIdsearch;

    /**
     * @param PsrLogger $logger
     * @param KlevuHelperConfig $searchHelperConfig
     * @param KlevuCatnavApiIdsearch $apiActionIdsearch
     */
    public function __construct(
        PsrLogger $logger,
        KlevuHelperConfig $searchHelperConfig,
        KlevuCatnavApiIdsearch $apiActionIdsearch
    ) {
        $this->logger = $logger;
        $this->searchHelperConfig = $searchHelperConfig;
        $this->apiActionIdsearch = $apiActionIdsearch;
    }

    /**
     * Get id using based on search term
     *
     * @param string $query
     * @return array
     * @throws LocalizedException
     */
    public function getSearchData(string $query): array
    {
        $klevu_parameters = [];
        try {
            $searchTerm = str_replace('%', '', $query);
            if (empty($klevu_parameters)) {
                $klevu_parameters = [
                'ticket' => $this->searchHelperConfig->getJsApiKey(),
                'noOfResults' => 2000,
                'term' => $searchTerm,
                'paginationStartsFrom' => 0,
                'enableFilters' => 'false',
                'klevuShowOutOfStockProducts' => 'false',
                'category' => 'KLEVU_PRODUCT',
                'visibility' => 'catalog'
                ];
            }
            $productIds = [];
            $data = $this->apiActionIdsearch->execute($klevu_parameters);
            $arrData = $data->getData()['result'];
            foreach ($arrData as $arr) {
                $productIds[] = $arr['id'];
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new LocalizedException(__('Some Issue with Search Query Api'));
        }
        return $productIds;
    }
}
