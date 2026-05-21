<?php
// @codingStandardsIgnoreFile

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Plugin;

use Codilar\CustomApi\Model\Relevance;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\EntityManager\Operation\Read\ReadExtensions;
use Magento\Framework\Serialize\Serializer\Json;
use Codilar\CustomApi\Model\KlevuSearch;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;

class RelevanceGetListPlugin
{
    /**
     * @var Relevance
     */
    private Relevance $relevance;

    /**
     * @var KlevuSearch
     */
    private KlevuSearch $klevuSearch;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var JoinProcessorInterface
     */
    private JoinProcessorInterface $extensionAttributesJoinProcessor;

    /**
     * @var CollectionProcessorInterface
     */
    protected CollectionProcessorInterface $collectionProcessor;

    /**
     * @var ReadExtensions
     */
    private ReadExtensions $readExtensions;

    /**
     * @var ProductSearchResultsInterfaceFactory
     */
    private ProductSearchResultsInterfaceFactory $searchResultsFactory;

    private FilterBuilder $filterBuilder;
    private FilterGroupBuilder $filterGroupBuilder;

    protected $instancesById = [];

    protected $instances = [];

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serializer;

    private $cacheLimit = 0;

    /**
     * @param Relevance $relevance
     * @param KlevuSearch $klevuSearch
     * @param CollectionFactory $collectionFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ReadExtensions $readExtensions
     * @param ProductSearchResultsInterfaceFactory $searchResultsFactory
     * @param Json|null $serializer
     * @param int $cacheLimit
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        Relevance $relevance,
        KlevuSearch $klevuSearch,
        CollectionFactory $collectionFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ReadExtensions $readExtensions,
        ProductSearchResultsInterfaceFactory $searchResultsFactory,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null,
        $cacheLimit = 1000,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->relevance = $relevance;
        $this->klevuSearch = $klevuSearch;
        $this->collectionFactory = $collectionFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->readExtensions = $readExtensions;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
        $this->cacheLimit = (int)$cacheLimit;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Around plugin for getlist
     *
     * @param ProductRepository $subject
     * @param \Closure $proceed
     * @param SearchCriteriaInterface $searchCriteria
     * @return ProductSearchResultsInterface|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundGetList(
        ProductRepository       $subject,
        \Closure                $proceed,
        SearchCriteriaInterface $searchCriteria
    ) {
        $ids =[];
        $searchIds = [];
        $name =null;
        $filterGroups = $searchCriteria->getFilterGroups();
        foreach ($filterGroups as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'category_id') {
                    $ids = $this->relevance->getSearchFilters($filter->getValue());
                }
                if ($filter->getField() === 'name') {
                    $searchIds = $this->klevuSearch->getSearchData($filter->getValue());
                    $name = $filter->getValue();
                }
            }
        }

        $searchIdsReverse = array_reverse($searchIds);
        $idReversed = array_reverse($ids);
        $isZeroReversed = false;
        if (count($idReversed) < 1) {
            $isZeroReversed = true;
            $idReversed = ['0'];
        }
        $sortOrders = $searchCriteria->getSortOrders();
        $fieldValue = null;
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                if ($sortOrder->getField() === 'personalized') {
                    $fieldValue = $sortOrder->getField();
                    break;
                }
            }
        }

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);

        $collection->addAttributeToSelect('*');

        if ($fieldValue == "personalized" && isset($name)) {
            $collection->getSelect()->order(new \Zend_Db_Expr('FIELD(e.entity_id,' .
                implode(',', $searchIdsReverse) . ') DESC'));
        } elseif ($fieldValue == "personalized" && count($idReversed) > 0) {
            $collection->getSelect()->order(new \Zend_Db_Expr('FIELD(e.entity_id,' .
                implode(',', $idReversed) . ') DESC'));
        } else {
            if (!$isZeroReversed) {
                $this->addProductIdsFilterToSearchCriteria($searchCriteria, $idReversed);
            }
            return $proceed($searchCriteria);
        }
        if (!empty($idReversed)) {
            $collection->addAttributeToFilter('entity_id', ['in' => $idReversed]);
        }
        $collection->joinAttribute(
            'status',
            'catalog_product/status',
            'entity_id',
            null,
            'inner'
        );
        $collection->joinAttribute(
            'visibility',
            'catalog_product/visibility',
            'entity_id',
            null,
            'inner'
        );
        $this->joinPositionField($collection, $searchCriteria);
        $this->collectionProcessor->process($searchCriteria, $collection);
        $collection->load();

        $collection->addCategoryIds();
        $this->addExtensionAttributes($collection);
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        foreach ($collection->getItems() as $product) {
            $this->cacheProduct($this->getCacheKey([false, $product->getStoreId()]), $product);
        }
        return $searchResult;
    }

    /**
     * Add productIds to search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param array $ids
     * @return void
     */
    private function addProductIdsFilterToSearchCriteria($searchCriteria, $ids)
    {
        $entityGroup = $this->filterGroupBuilder->create();
        $entityFilter = $this->filterBuilder->create();
        $entityFilter->setField("entity_id")
            ->setConditionType("in")
            ->setValue(implode(",", $ids));
        $entityGroup->setFilters([$entityFilter]);
        $filterGroups = $searchCriteria->getFilterGroups();
        $filterGroups[] = $entityGroup;
        $searchCriteria->setFilterGroups($filterGroups);
    }


    /**
     * Retrieve collection processor
     *
     * @deprecated 102.0.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!isset($this->collectionProcessor)) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
            // phpstan:ignore "Class Magento\Catalog\Model\Api\SearchCriteria\ProductCollectionProcessor not found."
                \Magento\Catalog\Model\Api\SearchCriteria\ProductCollectionProcessor::class
            );
        }
        return $this->collectionProcessor;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    private function addExtensionAttributes(Collection $collection) : Collection
    {
        foreach ($collection->getItems() as $item) {
            $this->readExtensions->execute($item);
        }
        return $collection;
    }

    /**
     * @param $cacheKey
     * @param ProductInterface $product
     * @return void
     */
    private function cacheProduct($cacheKey, ProductInterface $product)
    {
        $this->instancesById[$product->getId()][$cacheKey] = $product;
        $this->saveProductInLocalCache($product, $cacheKey);

        if ($this->cacheLimit && count($this->instances) > $this->cacheLimit) {
            $offset = round($this->cacheLimit / -2);
            $this->instancesById = array_slice($this->instancesById, $offset, null, true);
            $this->instances = array_slice($this->instances, $offset, null, true);
        }
    }

    /**
     * @param Product $product
     * @param string $cacheKey
     * @return void
     */
    private function saveProductInLocalCache(Product $product, string $cacheKey): void
    {
        $preparedSku = $this->prepareSku($product->getSku());
        $this->instances[$preparedSku][$cacheKey] = $product;
    }

    /**
     * @param string $sku
     * @return string
     */
    private function prepareSku(string $sku): string
    {
        return mb_strtolower(trim($sku));
    }

    /**
     * @param $data
     * @return string
     */
    protected function getCacheKey($data)
    {
        $serializeData = [];
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                $serializeData[$key] = $value->getId();
            } else {
                $serializeData[$key] = $value;
            }
        }
        $serializeData = $this->serializer->serialize($serializeData);
        return sha1($serializeData);
    }

    /**
     * Join category position field to make sorting by position possible.
     *
     * @param Collection $collection
     * @param SearchCriteriaInterface $searchCriteria
     * @return void
     */
    private function joinPositionField(
        Collection $collection,
        SearchCriteriaInterface $searchCriteria
    ): void {
        $categoryIds = [[]];
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'category_id') {
                    $filterValue = $filter->getValue();
                    $categoryIds[] = is_array($filterValue) ? $filterValue : explode(',', $filterValue);
                }
            }
        }
        $categoryIds = array_unique(array_merge(...$categoryIds));
        if (count($categoryIds) === 1) {
            $collection->joinField(
                'position',
                'catalog_category_product',
                'position',
                'product_id=entity_id',
                ['category_id' => current($categoryIds)],
                'left'
            );
        }
    }
}
