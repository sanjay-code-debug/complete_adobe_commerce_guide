<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model\Helper;

use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as Collection;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\EntityManager\Operation\Read\ReadExtensions;

class Search
{
    /**
     * @var ProductSearchResultsInterfaceFactory
     */
    private ProductSearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var JoinProcessorInterface
     */
    private JoinProcessorInterface $extensionAttributesJoinProcessor;

    /**
     * @var ReadExtensions
     */
    private ReadExtensions $readExtensions;


    public function __construct(
        ProductSearchResultsInterfaceFactory $searchResultsFactory,
        Collection $collection,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ReadExtensions $readExtensions
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collection = $collection;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->readExtensions = $readExtensions;
    }

    /**
     * Get the product based on ids
     *
     * @param SearchResultInterface $searchApiResult
     * @return ProductSearchResultsInterface
     */
    public function getProductResult(SearchResultInterface $searchApiResult)
    {
        $searchResult = $this->searchResultsFactory->create();
        $pageSize = $searchApiResult->getSearchCriteria()->getPageSize();
        $currentPage = $searchApiResult->getSearchCriteria()->getCurrentPage();

        if (empty($searchApiResult->getItems()) || !$this->hasItemsForPage($searchApiResult, $pageSize, $currentPage)) {
            $searchResult->setSearchCriteria($searchApiResult->getSearchCriteria());
            return $searchResult;
        }

        $currentPage = max($currentPage, 0); // Ensure currentPage is not negative.

        $items = $this->sliceItems(
            $searchApiResult->getItems(),
            $pageSize,
            $currentPage
        );
        $ids = [];
        foreach ($items as $item) {
            $ids[] = (int)$item->getId();
        }

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->collection->create();
        $this->extensionAttributesJoinProcessor->process($collection);
        $collection->addAttributeToSelect('*');
        $collection->getSelect()
            ->where('e.entity_id IN (?)', $ids)
            ->reset(\Magento\Framework\DB\Select::ORDER);
        $sortOrder = $searchApiResult->getSearchCriteria()
            ->getSortOrders();
        if (!empty($sortOrder['price']) && $collection->getLimitationFilters()->isUsingPriceIndex()) {
            $sortDirection = $sortOrder['price'];
            $collection->getSelect()
                ->order(
                    new \Zend_Db_Expr("price_index.min_price = 0, price_index.min_price {$sortDirection}")
                );
        } else {
            $orderList = join(',', $ids);
            $collection->getSelect()
                ->order(new \Zend_Db_Expr("FIELD(e.entity_id,$orderList)"));
        }
        $collection->load();

        $collection->addCategoryIds();
        $this->addExtensionAttributes($collection);
        $searchResult->setSearchCriteria($searchApiResult->getSearchCriteria());
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($searchApiResult->getTotalCount());
        return $searchResult;
    }

    /**
     * Check if there are items available for the specified page and page size.
     *
     * @param SearchResultInterface $searchApiResult
     * @param int $pageSize
     * @param int $currentPage
     * @return bool
     */
    private function hasItemsForPage(SearchResultInterface $searchApiResult, int $pageSize, int $currentPage): bool
    {
        $totalItems = $searchApiResult->getTotalCount();
        $maxAllowedPageNumber = ceil($totalItems / $pageSize);

        return $currentPage >= 0 && $currentPage < $maxAllowedPageNumber;
    }

    /**
     * Slice current items
     *
     * @param array $items
     * @param int $size
     * @param int $currentPage
     * @return array
     */
    private function sliceItems(array $items, int $size, int $currentPage): array
    {
        if ($size !== 0) {
            $itemsCount = count($items);
            $maxAllowedPageNumber = ceil($itemsCount / $size);
            if ($currentPage < 1) {
                $currentPage = 1;
            }
            if ($currentPage > $maxAllowedPageNumber) {
                $currentPage = $maxAllowedPageNumber;
            }
            $offset = $this->getOffset($currentPage, $size);
            $items = array_slice($items, $offset, $size);
        }
        return $items;
    }

    /**
     * Get offset for given page.
     *
     * @param int $pageNumber
     * @param int $pageSize
     * @return int
     */
    private function getOffset(int $pageNumber, int $pageSize): int
    {
        return ($pageNumber - 1) * $pageSize;
    }

    /**
     * @param $collection
     * @return mixed
     */
    private function addExtensionAttributes($collection)
    {
        foreach ($collection->getItems() as $item) {
            $this->readExtensions->execute($item);
        }
        return $collection;
    }
}
