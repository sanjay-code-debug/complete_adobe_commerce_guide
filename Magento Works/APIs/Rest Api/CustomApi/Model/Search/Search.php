<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model\Search;

use Codilar\CustomApi\Api\KlevuCustomSearchInterface as SearchInterface;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\Search\Request\Builder;
use Magento\Framework\Search\SearchEngineInterface;
use Magento\Framework\Search\SearchResponseBuilder;
use Magento\Framework\Search\Search as MagentoSearch;
use Codilar\CustomApi\Model\Helper\Search as HelperSearch;

/**
 * Search API for all requests.
 */
class Search extends MagentoSearch implements SearchInterface
{
    /**
     * @var HelperSearch
     */
    private HelperSearch $helperSearch;

    /**
     * @param Builder $requestBuilder
     * @param ScopeResolverInterface $scopeResolver
     * @param SearchEngineInterface $searchEngine
     * @param SearchResponseBuilder $searchResponseBuilder
     * @param HelperSearch $helperSearch
     */
    public function __construct(
        Builder $requestBuilder,
        ScopeResolverInterface $scopeResolver,
        SearchEngineInterface $searchEngine,
        SearchResponseBuilder $searchResponseBuilder,
        HelperSearch $helperSearch
    ) {
        parent::__construct(
            $requestBuilder,
            $scopeResolver,
            $searchEngine,
            $searchResponseBuilder
        );
        $this->helperSearch = $helperSearch;
    }

    /**
     * @inheritdoc
     */
    public function productSearch(SearchCriteriaInterface $searchCriteria)
    {
        $currentPage = max($searchCriteria->getCurrentPage() - 1, 0);
        $searchCriteria->setCurrentPage((int) $currentPage);
        //foreach added to convert comma separated attributes values in array as elasticsearch expects the same
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fieldsToCheck = ["material_f", "category_id", "color_f", "chararteristics", "shape", "size"];
            foreach ($filterGroup->getFilters() as $filter) {
                if (in_array($filter->getField(), $fieldsToCheck)) {
                    if ($filter->getField() == "category_id") {
                        $filter->setField("category_ids");
                    }
                    $valuesToArray = explode(",", $filter->getValue());
                    $filter->setValue($valuesToArray);
                }
            }
        }
        $searchResult = parent::search($searchCriteria);
        return $this->helperSearch->getProductResult($searchResult);
    }
}
