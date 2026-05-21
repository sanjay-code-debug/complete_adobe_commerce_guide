<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use MagePsycho\RegionCityPro\Api\Data\CitySearchResultsInterface;

interface CityRepositoryInterface
{
    /**
     * @api
     * @param SearchCriteriaInterface $searchCriteria
     * @return CitySearchResultsInterface
     */
    public function getCities(SearchCriteriaInterface $searchCriteria);
}
