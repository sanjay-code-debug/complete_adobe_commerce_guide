<?php
namespace Codilar\CustomApi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductRepositoryInterface
{
    /**
     * @param int categoryId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @param array productIds
     * @return \Codilar\CustomApi\Api\Data\ProductSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addAvailableFiltersUpdated($categoryId, SearchCriteriaInterface $searchCriteria, $productIds = null);

    /**
     * @param int categoryId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Codilar\CustomApi\Api\Data\ProductSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductListUpdated($categoryId, SearchCriteriaInterface $searchCriteria);

}
