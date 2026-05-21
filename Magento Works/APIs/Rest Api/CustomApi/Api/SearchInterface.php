<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\ProductSearchResultsInterface;
use Magento\Framework\Api\Search\SearchCriteriaInterface;

interface SearchInterface
{
    /**
     * Make Full Text Search and return products data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Codilar\CustomApi\Api\Data\ProductSearchResultsInterface
     */
    public function search(SearchCriteriaInterface $searchCriteria);

    /**
     * Get search product filters
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ProductSearchResultsInterface
     */
    public function searchFilters(SearchCriteriaInterface $searchCriteria);

}
