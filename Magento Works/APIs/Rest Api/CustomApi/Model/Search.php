<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model;

use Amasty\Shopby\Model\Search\SearchCriteriaBuilderProvider;
use Codilar\CustomApi\Api\Data\ProductSearchResultsInterface;
use Codilar\CustomApi\Api\Data\ProductSearchResultsInterfaceFactory as MProductSearchResultsFactory;
use Codilar\CustomApi\Api\SearchInterface;
use Codilar\CustomApi\Model\Helper\Data as HelperData;
use Codilar\CustomApi\Model\Helper\Search as HelperSearch;
use Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\Layer\Category\FilterableAttributeList;
use Magento\Catalog\Model\Layer\FilterListFactory;
use Magento\Catalog\Model\Layer\Search as SearchLayer;
use Magento\Catalog\Model\Layer\SearchFactory as SearchLayerFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Helper\Stock;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Search\Request\Builder;
use Magento\Framework\Search\Search as MageSearch;
use Magento\Framework\Search\SearchEngineInterface;
use Magento\Framework\Search\SearchResponseBuilder;
use Magento\Framework\UrlInterface;
use Magento\Search\Api\SearchInterface as MagentoSearchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Swatches\Helper\Data as SwatchesHelper;
use Magento\Swatches\Helper\Media as SwatchesMedia;

class Search extends MageSearch implements SearchInterface
{

    /**
     * @var StoreManagerInterface
     */
    private $storeManagerInterface;

    /**
     * @var HelperData
     */
    private $helperData;

    public const PRICE = 'price';

    public const STORE_CODE ="en";

    /**
     * @var FilterListFactory
     */
    protected $filterListFactory;

    /**
     * @var FilterableAttributeList
     */
    protected $filterableAttributes;

    /**
     * @var SearchLayer
     */
    protected $searchLayer;

    /**
     * @var SearchLayerFactory
     */
    protected $searchLayerFactory;

    protected MagentoSearchInterface $search;

    /**
     * @var SearchCriteriaBuilderProvider
     */
    private $searchCriteriaBuilderProvider;

    protected CollectionFactory $collectionFactory;

    /**
     * Category Repository
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var Stock
     */
    protected $stock;

    /**
     * @var SwatchesHelper
     */
    protected $swatchesHelper;

    /**
     * @var SwatchesMedia
     */
    protected $swatchesMedia;

    /**
     * @var array
     */
    protected $availableFilters = [];

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var HelperSearch
     */
    private HelperSearch $helperSearch;

    private \Magento\Framework\Webapi\ServiceOutputProcessor $serviceOutputProcessor;

    /**
     * @param Builder $requestBuilder
     * @param ScopeResolverInterface $scopeResolver
     * @param SearchEngineInterface $searchEngine
     * @param SearchResponseBuilder $searchResponseBuilder
     * @param StoreManagerInterface $StoreManagerInterface
     * @param MProductSearchResultsFactory $mSearchResultsFactory
     * @param ProductSearchResultsInterfaceFactory $searchResultsFactory
     * @param FilterListFactory $filterListFactory
     * @param FilterableAttributeList $filterableAttributes
     * @param SearchLayer $searchLayer
     * @param MagentoSearchInterface $search
     * @param SearchCriteriaBuilderProvider $searchCriteriaBuilderProvider
     * @param CollectionFactory $collectionFactory
     * @param CategoryRepository $categoryRepository
     * @param Stock $stock
     * @param SwatchesHelper $swatchesHelper
     * @param SwatchesMedia $swatchesMedia
     */
    public function __construct(
        Builder $requestBuilder,
        ScopeResolverInterface $scopeResolver,
        SearchEngineInterface $searchEngine,
        SearchResponseBuilder $searchResponseBuilder,
        StoreManagerInterface $StoreManagerInterface,
        MProductSearchResultsFactory $mSearchResultsFactory,
        FilterListFactory $filterListFactory,
        FilterableAttributeList $filterableAttributes,
        SearchLayer $searchLayer,
        MagentoSearchInterface $search,
        SearchCriteriaBuilderProvider $searchCriteriaBuilderProvider,
        CollectionFactory $collectionFactory,
        CategoryRepository $categoryRepository,
        Stock $stock,
        SwatchesHelper $swatchesHelper,
        SwatchesMedia $swatchesMedia,
        ProductRepository $productRepository,
        HelperSearch $helperSearch,
        HelperData $helperData,
        SearchLayerFactory $searchLayerFactory,
        \Magento\Framework\Webapi\ServiceOutputProcessor $serviceOutputProcessor
    ) {
        parent::__construct(
            $requestBuilder,
            $scopeResolver,
            $searchEngine,
            $searchResponseBuilder
        );
        $this->storeManagerInterface = $StoreManagerInterface;
        $this->searchResultsFactory = $mSearchResultsFactory;
        $this->filterListFactory = $filterListFactory;
        $this->filterableAttributes = $filterableAttributes;
        $this->searchLayer = $searchLayer;
        $this->search = $search;
        $this->searchCriteriaBuilderProvider = $searchCriteriaBuilderProvider;
        $this->collectionFactory = $collectionFactory;
        $this->categoryRepository = $categoryRepository;
        $this->stock = $stock;
        $this->swatchesHelper = $swatchesHelper;
        $this->swatchesMedia = $swatchesMedia;
        $this->productRepository = $productRepository;
        $this->helperSearch = $helperSearch;
        $this->helperData = $helperData;
        $this->searchLayerFactory = $searchLayerFactory;
        $this->serviceOutputProcessor = $serviceOutputProcessor;
    }

    /**
     * @inheritdoc
     */
    public function search(SearchCriteriaInterface $searchCriteria)
    {
        $productIds = [];
        $searchResult = parent::search($searchCriteria);
        foreach ($searchResult->getItems() as $_searchResponse) {
            $productIds[] = $_searchResponse->getId();
        }

        $categoryId = $this->storeManagerInterface->getStore()->getRootCategoryId();
        //$searchCriteriaForProductListing = $this->searchCriteriaBuilder->addFilter('entity_id', implode(',',$productIds), 'in')->create();
        return $this->productRepository->getProductListUpdated($categoryId, $searchCriteria);
    }

    /**
     * @inheritdoc
     */
    public function searchFilters(SearchCriteriaInterface $searchCriteria)
    {
        $productIds = [];
        $category = $this->storeManagerInterface->getStore()->getRootCategoryId();
        return $this->getFiltersWithSearchCriteriaFinal($category, $searchCriteria, $productIds);
    }

    /**
     * @param $categoryId
     * @param $searchCriteria
     * @param $productIds
     * @return ProductSearchResultsInterface
     * @throws LocalizedException
     */
    public function getFiltersWithSearchCriteriaFinal($categoryId, $searchCriteria, $productIds)
    {
        $searchCriteriaWithoutCategory = $this->helperData->cloneSearchCriteria($searchCriteria);
        $filterGroups = $searchCriteriaWithoutCategory->getFilterGroups();
        foreach ($filterGroups as $filterGroup) {
            $filters = $filterGroup->getFilters();
            foreach ($filters as $key => $filter) {
                if ($filter->getField() == 'category_id') {
                    unset($filters[$key]);
                }
            }
            $filterGroup->setFilters($filters);
        }

        $response = [];
        $filtersWithoutCategory = $this->getSearchFiltersWithSearchCriteria($categoryId, $searchCriteriaWithoutCategory, $productIds, false);
        $filtersWithoutCategory = $this->serviceOutputProcessor->convertValue($filtersWithoutCategory, ProductSearchResultsInterface::class);
        $filtersWithoutCategory = json_decode(json_encode($filtersWithoutCategory), true);
        foreach ($filtersWithoutCategory['available_filters'] as $filter) {
            if ($filter['attribute_code'] === 'category_id') {
                $response[] = $filter;
                break;
            }
        }

        $filtersWithCategory = $this->getSearchFiltersWithSearchCriteria($categoryId, $searchCriteria, $productIds, true);
        $filtersWithCategory = $this->serviceOutputProcessor->convertValue($filtersWithCategory, ProductSearchResultsInterface::class);
        $filtersWithCategory = json_decode(json_encode($filtersWithCategory), true);
        foreach ($filtersWithCategory['available_filters'] as $filter) {
            if ($filter['attribute_code'] !== 'category_id') {
                $response[] = $filter;
            }
        }

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setAvailableFilters($response);
        return $searchResult;
    }
    /**
     * @param $category
     * @param $searchCriteria
     * @param array $productIds
     * @return ProductSearchResultsInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getSearchFiltersWithSearchCriteria($category, $searchCriteria, $productIds = [], $isWithCategory)
    {
        $this->availableFilters = [];
        $selectedFilters = [];
        $ignoreFilters = [self::PRICE];

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        $filterList = $this->filterListFactory->create(
            [
                'filterableAttributes' => $this->filterableAttributes
            ]
        );

        $layer = $this->searchLayerFactory->create();
        $layer->setCurrentCategory($category);

        if (!empty($productIds)) {
            $layer->getProductCollection()->addAttributeToFilter('entity_id', ['in' => $productIds]);
        }

        $filterGroups = $searchCriteria->getFilterGroups();
        $from = $to = "";

        foreach ($filterGroups as $k => $filters) {
            foreach ($filters->getFilters() as $filter) {
                if ($filter->getField() == "search_term") {
                    $layer->getProductCollection()->addSearchFilter($filter->getValue());
                    continue;
                }
                $selectedFilters[$filter->getField()] = explode(',', $filter->getValue());
                $filterValueArray = explode(",", $filter->getValue());
                if ($filter->getField() == "category_id") {
                    $layer->getProductCollection()->addFieldToFilter('category_ids', $filterValueArray);
                    $checkLayerProducts = true;
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                if ($filter->getField() == self::PRICE) {
                    if ($filter->getConditionType() == "lteq") {
                        $to = $filter->getValue();
                    }
                    if ($filter->getConditionType() == "gteq") {
                        $from = $filter->getValue();
                    }
                } else {
                    $this->addFieldToFilter($filter->getField(), $filterValueArray);
                }
            }
        }
        if ($from && $to) {
            $layer->getProductCollection()->addFieldToFilter(
                'price',
                ['from' => $from, 'to' => $to]
            );
        }

        $filters = $filterList->getFilters($layer);

        $priceFacetedData = $layer->getProductCollection()->getFacetedData('price', null);

        $data = [];
        foreach ($filters as $filter) {
            if($isWithCategory && $filter instanceof \Magento\Catalog\Model\Layer\Filter\Category) {
                continue;
            } elseif (!$isWithCategory && !$filter instanceof \Magento\Catalog\Model\Layer\Filter\Category) {
                continue;
            }
            $attributeCode = $attributeType = '';
            $filterValues = [];

            if ($filter->getName() == 'Category') {
                $attributeCode = 'category_id';
                $attributeType = 'checkbox';
            }

            if ($filter->hasAttributeModel()) {
                $attributeCode = $filter->getAttributeModel()->getAttributeCode();
                $attributeType = $filter->getAttributeModel()->getFrontendInput();

                if ($attributeType == 'select' || $attributeType == 'multiselect') {
                    $attributeType = 'checkbox';
                    if ($additionalData = $filter->getAttributeModel()->getAdditionalData()) {
                        $_additionalData = json_decode($additionalData, true);
                        if (isset($_additionalData['swatch_input_type']) &&
                            in_array($_additionalData['swatch_input_type'], ['text', 'visual'])) {
                            $attributeType = 'swatch';
                        }
                    }
                }
            }

            $availablefilter = $filter->getName();
            if ($attributeType == 'price') {
                $getMinMaxPrice = $this->getMinMaxPrice($category, $productIds);
                if (!empty($priceFacetedData)) {
                    $filterValues = [];
                    foreach ($priceFacetedData as $value) {
                        $min_from = $value['from'];
                        $max_to = $value['to'];
                        $filterValues[] = [
                            "min" => $min_from,
                            "max" => $max_to
                        ];
                    }
                    $attributeType = "range";
                }
            } else {
                if ($availablefilter == 'Category') {
                    $items = $filter->getItems();
                    $j = 0;
                    foreach ($items as $item) {
                        $filterValues[$j]['count'] = $item->getCount();
                        $filterValues[$j]['label'] = strip_tags($item->getLabel());
                        $filterValues[$j]['value'] = $item->getValue();

                        if ($this->storeManagerInterface->getStore()->getCode() == self::STORE_CODE) {
                            $subcategories = $this->getSubCategories($item->getValue(), $searchCriteria, $productIds);
                            $i = 0;
                            foreach ($subcategories as $subcategory) {
                                $filterValues[$j]['sub_categories'][$i] = [
                                    'count' => $subcategory['count'],
                                    'label' => $subcategory['name'],
                                    'value' => $subcategory['id']
                                ];
                                if (isset($selectedFilters[$attributeCode]) &&
                                    in_array($subcategory['id'], $selectedFilters[$attributeCode])) {
                                    $filterValues[$j]['sub_categories'][$i]['selected'] = true;
                                }
                                $i++;
                            }
                        }
                        if (isset($selectedFilters[$attributeCode]) &&
                            in_array($item->getValue(), $selectedFilters[$attributeCode])) {
                            $filterValues[$j]['selected'] = true;
                        }
                        $j++;
                    }
                } else {
                    $attribute = $filter->getAttributeModel();
                    $searchCriteriaAttribute = $layer->getProductCollection()->getSearchCriteria([$attributeCode]);
                    $attributeSearchResult = $this->search->search($searchCriteriaAttribute);
                    try {
                        $optionsFacetedData = $layer->getProductCollection()->getFacetedData(
                            $attributeCode,
                            $attributeSearchResult
                        );
                    } catch (\Exception $e) {
                        continue;
                    }
                    $j = 0;
                    if (!empty($optionsFacetedData)) {
                        foreach ($optionsFacetedData as $option) {
                            $label = $attribute->getSource()->getOptionText($option['value']);
                            if ($label) {
                                $filterValues[$j]['count'] = $option['count'];
                                $filterValues[$j]['label'] = $label;
                                $filterValues[$j]['value'] = $option['value'];

                                if ($attributeType == 'swatch' &&
                                    ($atributeSwatchHashcode = $this->getAtributeSwatchHashcode($option['value']))) {
                                    $filterValues[$j]['swatch_value'] = $atributeSwatchHashcode;
                                }
                                if (isset($selectedFilters[$attributeCode]) &&
                                    in_array($option['value'], $selectedFilters[$attributeCode])) {
                                    $filterValues[$j]['selected'] = true;
                                }
                                $j++;
                            }
                        }
                    }
                }
            }

            if ((!empty($filterValues)) || $attributeType == 'range') {
                if (count($filterValues) == 0 &&
                    !array_key_exists($attributeCode, $selectedFilters) && !in_array($attributeCode, $ignoreFilters)) {
                    continue;
                }
                if ($attributeCode != self::PRICE) {
                    if ($attributeType == "range" && $from && $to) {
                        $data[] = [
                            "filter" => $filter,
                            "attribute_code" => $attributeCode,
                            "name" => $availablefilter,
                            "options" => $filterValues,
                            "attribute_type" => $attributeType,
                            "selected_price" => ['min' => floatval($from), 'max' => floatval($to)]
                        ];
                    } else {
                        $data[] = [
                            "filter" => $filter,
                            "attribute_code" => $attributeCode,
                            "name" => $availablefilter,
                            "options" => $filterValues,
                            "attribute_type" => $attributeType
                        ];
                    }
                }

                $this->availableFilters = $data;
            }
        }
        if (count($this->availableFilters) > 0) {
            $searchResult->setAvailableFilters($this->availableFilters);
        }

        return $searchResult;
    }

    /**
     * @param mixed $field
     * @param null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (!is_array($condition) || !in_array(key($condition), ['from', 'to'], true)) {
            $this->searchCriteriaBuilderProvider->addFilter($field, $condition);
        } else {
            if (isset($condition['from'])) {
                $this->searchCriteriaBuilderProvider->addFilter("{$field}.from", $condition['from']);
            }
            if (isset($condition['to'])) {
                $this->searchCriteriaBuilderProvider->addFilter("{$field}.to", $condition['to']);
            }
        }
        return $this;
    }

    /**
     * @param int $categoryId
     * @param array $productIds
     * @return array
     * @throws NoSuchEntityException
     */
    public function getMinMaxPrice($categoryId, $productIds)
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect('price');
        $collection->setOrder('price', 'DESC');

        $category = $this->categoryRepository->get($categoryId, $this->storeManagerInterface->getStore()->getId());
        $collection->addCategoryFilter($category);

        if (!empty($productIds)) {
            $collection->addAttributeToFilter('entity_id', ['in' => $productIds]);
        }

        $this->stock->addIsInStockFilterToCollection($collection); // add in stock filter

        if ($collection->count() == 1) {
            return [];
        }
        $max = $collection->getMaxPrice();
        $min = $collection->getMinPrice();

        return [
            'max' => $max,
            'min' => $min
        ];
    }

    /**
     * @param int $categoryId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCategoryImage($categoryId)
    {
        $categoryImage = null;
        $category = $this->categoryRepository->get($categoryId, $this->storeManagerInterface->getStore()->getId());
        if ($category->getThumbnailImage()) {
            $name = basename($category->getThumbnailImage());
            $path = 'catalog/category/' . $name;
            $categoryImage = $this->storeManagerInterface->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $path;
        }
        return $categoryImage;
    }

//    /**
//     * @param int $categoryId
//     * @return array
//     * @throws NoSuchEntityException
//     */
//    public function getSubCategories($categoryId)
//    {
//        $category = $this->categoryRepository->get($categoryId, $this->storeManagerInterface->getStore()->getId());
//        $subCategories = $category->getChildrenCategories();
//        $subCategoriesData = [];
//        foreach ($subCategories as $subCategory) {
//            if ($subCategory->getIsActive() == 1) {
//                $subCategoriesData[] = [
//                    'name' => $subCategory->getName(),
//                    'id' => $subCategory->getEntityId(),
//                    'count' => $subCategory->getProductCount()
//                ];
//            }
//        }
//        return $subCategoriesData;
//    }

    /**
     * Retrieve subcategories for a given category with product counts based on search criteria.
     *
     * @param int $categoryId The ID of the parent category.
     * @param mixed $searchCriteria The search criteria used to fetch product collection.
     * @param mixed $productIds The search criteria used to fetch product collection.
     * @return array An array containing subcategory data with updated product counts.
     */
    public function getSubCategories($categoryId, $searchCriteria, $productIds)
    {
        $category = $this->categoryRepository->get($categoryId, $this->storeManagerInterface->getStore()->getId());
        $subCategories = $category->getChildrenCategories();

        $subCategoriesData = [];
        foreach ($subCategories as $subCategory) {
            if ($subCategory->getIsActive() == 1) {
                $subCategoryId = $subCategory->getEntityId();
                $subCategoryCount = $this->getSubCategoryProductCount($subCategory, $searchCriteria, $productIds);
                if ($subCategoryCount === 0) {
                    continue;
                }
                $subCategoriesData[] = [
                    'name' => $subCategory->getName(),
                    'id' => $subCategoryId,
                    'count' => $subCategoryCount
                ];
            }
        }

        return $subCategoriesData;
    }

    /**
     * Get the product count for a specific subcategory based on search criteria.
     *
     * @param int $subCategoryId The ID of the subcategory.
     * @param mixed $searchCriteria The search criteria used to fetch product collection.
     * @return int The count of products in the subcategory that match the search criteria.
     */
    public function getSubCategoryProductCount($subCategory, $searchCriteria, $productIds)
    {
        // Assuming you have a method to get a product collection based on the subcategory and search criteria
        $subCategoryProductCollection = $this->getProductCollectionForSubCategory($subCategory, $productIds);

        // Get the count of products in the subcategory collection
        $subCategoryProductCount = $subCategoryProductCollection->getSize();

        return $subCategoryProductCount;
    }

    /**
     * Get a product collection for a specific subcategory based on search criteria and product IDs.
     *
     * @param int $subCategoryId The ID of the subcategory.
     * @param mixed $searchCriteria The search criteria used to fetch product collection.
     * @param array $productIds The list of product IDs in the search result.
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection A product collection.
     */
    public function getProductCollectionForSubCategory($subCategory, $productIds)
    {
        // Retrieve product collection factory
        $productCollection = $this->collectionFactory->create();

//        $category = $this->categoryFactory->create()->load($subCategoryId);
        // Apply search criteria to the product collection
        $productCollection->addCategoryFilter($subCategory);
        $productCollection->addIdFilter($productIds); // Filter products by the provided IDs

        // Additional filters and sorting logic can be applied here

        return $productCollection;
    }

    /**
     * @param $optionid
     * @return mixed|string|null
     */
    public function getAtributeSwatchHashcode($optionid)
    {
        $hashcodeData = $this->swatchesHelper->getSwatchesByOptionsId([$optionid]);
        if (isset($hashcodeData[$optionid]['value'])) {
            $imageValue = $this->swatchesMedia->getSwatchAttributeImage(
                'swatch_thumb',
                $hashcodeData[$optionid]['value']
            );
            if ($imageValue) {
                return $this->swatchesMedia->getSwatchMediaUrl() . $hashcodeData[$optionid]['value'];
            }
            return $option['swatch_value'] = $hashcodeData[$optionid]['value'];
        }

        return null;
    }
}
