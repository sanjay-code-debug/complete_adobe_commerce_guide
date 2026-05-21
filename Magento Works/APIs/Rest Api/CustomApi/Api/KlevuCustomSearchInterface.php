<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Api;

use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Framework\Api\Search\SearchCriteriaInterface;

interface KlevuCustomSearchInterface
{
    /**
     * Product Search
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ProductSearchResultsInterface
     */
    public function productSearch(SearchCriteriaInterface $searchCriteria);
}
