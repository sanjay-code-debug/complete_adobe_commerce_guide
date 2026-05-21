<?php
// @codingStandardsIgnoreFile

namespace Codilar\CustomApi\Model;

use Amasty\Shopby\Model\Search\SearchCriteriaBuilderProvider;
use Codilar\CustomApi\Api\Data\ProductSearchResultsInterface;
use Codilar\CustomApi\Api\Data\ProductSearchResultsInterfaceFactory as MProductSearchResultsFactory;
use Codilar\CustomApi\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use Magento\Catalog\Helper\ImageFactory as PlaceHolderImage;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\Layer\Category\FilterableAttributeList;
use Magento\Catalog\Model\Layer\FilterListFactory;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Layer\ResolverFactory;
use Magento\Catalog\Model\Layer\Search as SearchLayer;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Helper\Stock;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\Search\FilterGroupBuilderFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\EntityManager\Operation\Read\ReadExtensions;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\InventoryApi\Api\Data\StockInterface;
use Magento\InventorySales\Model\StockByWebsiteIdResolver;
use Magento\Search\Api\SearchInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Swatches\Helper\Data as SwatchesHelper;
use Magento\Swatches\Helper\Media as SwatchesMedia;
use Magento\Wishlist\Model\WishlistFactory;

class ProductRepository implements ProductRepositoryInterface
{
    public const STORE_CODE = 'en';

    public const PRICE = 'price';

    protected StoreManagerInterface $storeManager;

    /**
     * @var FilterListFactory
     */
    protected $filterListFactory;

    /**
     * @var FilterableAttributeList
     */
    protected $filterableAttributes;

    protected Resolver $layerResolver;

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
     * @var SearchLayer
     */
    protected $searchLayer;

    /**
     * @var SwatchesHelper
     */
    protected $swatchesHelper;

    /**
     * @var SwatchesMedia
     */
    protected $swatchesMedia;

    /**
     * @var SearchInterface
     */
    private $search;

    /**
     * @var SearchCriteriaBuilderProvider
     */
    private $searchCriteriaBuilderProvider;

    /**
     * @var WishlistFactory
     */
    private $wishlistFactory;

    /**
     * @var Request
     */
    protected $requestApi;

    /**
     * Collection Processor
     *
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Emulation
     */
    private $appEmulation;

    /**
     * @var PlaceHolderImage
     */
    private $placeHolderImage;

    /**
     * @var StockByWebsiteIdResolver
     */
    private $stockByWebsiteIdResolver;

    public const DEFAULT_SORT_BY = "catalog/frontend/default_sort_by";

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    public const CREATED_AT_SORT = 'created_at';

    public const DEFAULT_DIRECTION = "ASC";

    /**
     * Read extensions
     *
     * @var ReadExtensions
     */
    protected $readExtensions;
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;
    /**
     * @var FilterGroupBuilderFactory
     */
    private FilterGroupBuilderFactory $filterGroupBuilderFactory;
    private \Magento\Framework\Webapi\ServiceOutputProcessor $serviceOutputProcessor;
    /**
     * @var ResolverFactory
     */
    private ResolverFactory $layerResolverFactory;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ProductSearchResultsInterfaceFactory $searchResultsFactory
     * @param MProductSearchResultsFactory $mSearchResultsFactory
     * @param FilterListFactory $filterListFactory
     * @param FilterableAttributeList $filterableAttributes
     * @param Resolver $layerResolver
     * @param CollectionFactory $collectionFactory
     * @param CategoryRepository $categoryRepository
     * @param Stock $stock
     * @param SearchLayer $searchLayer
     * @param SwatchesHelper $swatchesHelper
     * @param SwatchesMedia $swatchesMedia
     * @param SearchInterface $search
     * @param SearchCriteriaBuilderProvider $searchCriteriaBuilderProvider
     * @param WishlistFactory $wishlistFactory
     * @param Request $requestApi
     * @param Emulation $appEmulation
     * @param PlaceHolderImage $placeHolderImage
     * @param StockByWebsiteIdResolver $stockByWebsiteIdResolver
     * @param ScopeConfigInterface $scopeConfig
     * @param ReadExtensions|null $readExtensions
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ProductSearchResultsInterfaceFactory $searchResultsFactory,
        MProductSearchResultsFactory $mSearchResultsFactory,
        FilterListFactory $filterListFactory,
        FilterableAttributeList $filterableAttributes,
        Resolver $layerResolver,
        CollectionFactory $collectionFactory,
        CategoryRepository $categoryRepository,
        Stock $stock,
        SearchLayer $searchLayer,
        SwatchesHelper $swatchesHelper,
        SwatchesMedia $swatchesMedia,
        SearchInterface $search,
        SearchCriteriaBuilderProvider $searchCriteriaBuilderProvider,
        WishlistFactory $wishlistFactory,
        Request $requestApi,
        Emulation $appEmulation,
        PlaceHolderImage $placeHolderImage,
        StockByWebsiteIdResolver $stockByWebsiteIdResolver,
        ScopeConfigInterface $scopeConfig,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        FilterGroupBuilderFactory $filterGroupBuilderFactory,
        \Magento\Framework\Webapi\ServiceOutputProcessor $serviceOutputProcessor,
        ResolverFactory $layerResolverFactory,
        ReadExtensions $readExtensions = null,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->storeManager = $storeManager;
        $this->searchResultsFactory = $mSearchResultsFactory;
        $this->filterListFactory = $filterListFactory;
        $this->filterableAttributes = $filterableAttributes;
        $this->layerResolver = $layerResolver;
        $this->collectionFactory = $collectionFactory;
        $this->categoryRepository = $categoryRepository;
        $this->stock = $stock;
        $this->searchLayer = $searchLayer;
        $this->swatchesHelper = $swatchesHelper;
        $this->swatchesMedia = $swatchesMedia;
        $this->search = $search;
        $this->searchCriteriaBuilderProvider = $searchCriteriaBuilderProvider;
        $this->wishlistFactory = $wishlistFactory;
        $this->requestApi = $requestApi;
        $this->appEmulation = $appEmulation;
        $this->placeHolderImage = $placeHolderImage;
        $this->stockByWebsiteIdResolver = $stockByWebsiteIdResolver;
        $this->scopeConfig = $scopeConfig;
        $this->readExtensions = $readExtensions ?: ObjectManager::getInstance()
            ->get(ReadExtensions::class);
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->filterGroupBuilderFactory = $filterGroupBuilderFactory;
        $this->serviceOutputProcessor = $serviceOutputProcessor;
        $this->layerResolverFactory = $layerResolverFactory;
    }

    /**
     * @param $categoryId
     * @param $searchCriteria
     * @param $productIds
     * @return ProductSearchResultsInterface
     * @throws LocalizedException
     */
    public function addAvailableFiltersUpdated($categoryId, $searchCriteria, $productIds = [])
    {
        return $this->getFiltersWithSearchCriteriaFinal($categoryId, $searchCriteria, $productIds);
//        $filterGroups = $searchCriteria->getFilterGroups();
//        if (empty($filterGroups)) {
//            return $this->getFiltersWithOutSearchCriteria($categoryId, $searchCriteria, $productIds);
//        } else {
//            return $this->getFiltersWithSearchCriteriaFinal($categoryId, $searchCriteria, $productIds);
//        }
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
        $searchCriteriaWithoutCategory = $this->cloneSearchCriteria($searchCriteria);
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
        $filtersWithoutCategory = $this->getFiltersWithSearchCriteria($categoryId, $searchCriteriaWithoutCategory, $productIds, false);
        $filtersWithoutCategory = $this->serviceOutputProcessor->convertValue($filtersWithoutCategory, ProductSearchResultsInterface::class);
        $filtersWithoutCategory = json_decode(json_encode($filtersWithoutCategory), true);
        foreach ($filtersWithoutCategory['available_filters'] as $filter) {
            if($filter['attribute_code'] === 'category_id') {
                $response[] = $filter;
                break;
            }
        }


        $filtersWithCategory = $this->getFiltersWithSearchCriteria($categoryId, $searchCriteria, $productIds, true);
        $filtersWithCategory = $this->serviceOutputProcessor->convertValue($filtersWithCategory, ProductSearchResultsInterface::class);
        $filtersWithCategory = json_decode(json_encode($filtersWithCategory), true);
        foreach ($filtersWithCategory['available_filters'] as $filter) {
            if($filter['attribute_code'] !== 'category_id') {
                $response[] = $filter;
            }
        }

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setAvailableFilters($response);
        return $searchResult;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Magento\Framework\Api\SearchCriteria
     */
    public function cloneSearchCriteria($searchCriteria)
    {
        $builder = $this->searchCriteriaBuilderFactory->create();
        $builder->setCurrentPage($searchCriteria->getCurrentPage());
        $builder->setPageSize($searchCriteria->getPageSize());
        foreach ($searchCriteria->getSortOrders() ?? [] as $sortOrder) {
            $clonedSortOrder = clone $sortOrder;
            $builder->addSortOrder($clonedSortOrder);
        }
        $clonedFilterGroups = [];
        foreach ($searchCriteria->getFilterGroups() ?? [] as $filterGroup) {
            $filterGroupBuilder = $this->filterGroupBuilderFactory->create();
            foreach ($filterGroup->getFilters() ?? [] as $filter) {
                $clonedFilter = clone $filter;
                $filterGroupBuilder->addFilter($clonedFilter);
            }
            $clonedFilterGroups[] = $filterGroupBuilder->create();
        }
        $builder->setFilterGroups($clonedFilterGroups);
        return $builder->create();
    }
    /**
     * @param $categoryId
     * @param $searchCriteria
     * @param $productIds
     * @return ProductSearchResultsInterface
     * @throws LocalizedException
     */
    public function getFiltersWithOutSearchCriteria($categoryId, $searchCriteria, $productIds = [])
    {
        $selectedFilters = [];
        $category = $categoryId ?? $this->storeManager->getStore()->getRootCategoryId();
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        $filterList = $this->filterListFactory->create(
            [
                'filterableAttributes' => $this->filterableAttributes
            ]
        );

        $layer = $this->layerResolverFactory->create()->get();

        $layer->setCurrentCategory($category);
        $layer->getProductCollection();
        if (!empty($productIds)) {
            $layer->getProductCollection()->addAttributeToFilter('entity_id', ['in' => $productIds]);
        }

        $filters = $filterList->getFilters($layer);

        $layer->getProductCollection()->getMaxPrice();
        $layer->getProductCollection()->getMinPrice();

        $data = [];
        foreach ($filters as $filter) {
            $attributeCode = $attributeType = '';
            $filterValues = [];

            if ($filter->getName() == 'Categories') {
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
                        if (isset($_additionalData['swatch_input_type'])
                            && in_array($_additionalData['swatch_input_type'], ['text', 'visual'])) {
                            $attributeType = 'swatch';
                        }
                    }
                }
            }

            $availablefilter = $filter->getName();
            if ($attributeType == 'price') {
                $getMinMaxPrice = $this->getMinMaxPrice($category, $productIds);
                if (!empty($getMinMaxPrice)) {
                    $filterValues[] = [
                        "min" => $getMinMaxPrice['min'],
                        "max" => $getMinMaxPrice['max']
                    ];
                    $attributeType = "range";
                }
            } else {
                $items = $filter->getItems();
                $j = 0;
                foreach ($items as $item) {
                    $filterValues[$j]['count'] = $item->getCount();
                    $filterValues[$j]['label'] = strip_tags($item->getLabel());
                    $filterValues[$j]['value'] = $item->getValue();
                    if ($availablefilter == 'Categories') {
                        $categoryImage = $this->getCategoryImage($item->getValue());
                        if ($categoryImage) {
                            $filterValues[$j]['image'] = $categoryImage;
                        }
                        if ($this->storeManager->getStore()->getCode() == self::STORE_CODE) {
                            $subcategories = $this->getSubCategories($item->getValue());
                            $i = 0;
                            foreach ($subcategories as $subcategory) {
                                $subCategoryImage = $this->getCategoryImage($subcategory['id']);
                                $filterValues[$j]['sub_categories'][$i] = [
                                    'count' => $subcategory['count'],
                                    'label' => $subcategory['name'],
                                    'value' => $subcategory['id'],
                                    'image' => $subCategoryImage
                                ];
                                if (isset($selectedFilters[$attributeCode])
                                    && in_array($subcategory['id'], $selectedFilters[$attributeCode])) {
                                    $filterValues[$j]['sub_categories'][$i]['selected'] = true;
                                }
                                $i++;
                            }
                        }
                    }
                    if ($attributeType == 'swatch'
                        && ($atributeSwatchHashcode = $this->getAtributeSwatchHashcode($item->getValue()))) {
                        $filterValues[$j]['swatch_value'] = $atributeSwatchHashcode;
                    }
                    if (isset($selectedFilters[$attributeCode])
                        && in_array($item->getValue(), $selectedFilters[$attributeCode])) {
                        $filterValues[$j]['selected'] = true;
                    }

                    $j++;
                }
            }

            if ((!empty($filterValues) && count($filterValues) > 1) || $attributeType == 'range') {
                $data[] = [
                    "filter" => $filter,
                    "attribute_code" => $attributeCode,
                    "name" => $availablefilter,
                    "options" => $filterValues,
                    "attribute_type" => $attributeType
                ];

                $this->availableFilters = $data;
            }
        }

        if (count($this->availableFilters) > 0) {
            $searchResult->setAvailableFilters($this->availableFilters);
        }

        return $searchResult;
    }

    /**
     * @param $categoryId
     * @param $searchCriteria
     * @param $productIds
     * @return ProductSearchResultsInterface
     * @throws LocalizedException
     */
    public function getFiltersWithSearchCriteria($categoryId, $searchCriteria, $productIds = [], $isWithCategory)
    {
        $this->availableFilters = [];
        $selectedFilters = [];
        $ignoreFilters = [self::PRICE];

        $category = $categoryId ?? $this->storeManager->getStore()->getRootCategoryId();

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        $filterList = $this->filterListFactory->create(
            [
                'filterableAttributes' => $this->filterableAttributes
            ]
        );

        $layer = $this->layerResolverFactory->create()->get();

        $layer->setCurrentCategory($category);
        $layer->getProductCollection();
        if (!empty($productIds)) {
            $layer->getProductCollection()->addAttributeToFilter('entity_id', ['in' => $productIds]);
        }
        $filterGroups = $searchCriteria->getFilterGroups();
        $from = $to = "";
        foreach ($filterGroups as $k => $filters) {
            foreach ($filters->getFilters() as $filter) {
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
                        if (isset($_additionalData['swatch_input_type'])
                            && in_array($_additionalData['swatch_input_type'], ['text', 'visual'])) {
                            $attributeType = 'swatch';
                        }
                    }
                }
            }

            $availablefilter = $filter->getName();
            if ($attributeType == 'price') {
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
                        $label = str_replace('&amp;', '&', $item->getLabel());
                        $filterValues[$j]['label'] = strip_tags($label);
                        $filterValues[$j]['value'] = $item->getValue();

                        $categoryImage = $this->getCategoryImage($item->getValue());
                        if ($categoryImage) {
                            $filterValues[$j]['image'] = $categoryImage;
                        }
                        if ($this->storeManager->getStore()->getCode() == self::STORE_CODE) {
                            $subcategories = $this->getSubCategories($item->getValue());
                            $i = 0;
                            foreach ($subcategories as $subcategory) {
                                $subCategoryImage = $this->getCategoryImage($subcategory['id']);
                                $filterValues[$j]['sub_categories'][$i] = [
                                    'count' => $subcategory['count'],
                                    'label' => $subcategory['name'],
                                    'value' => $subcategory['id'],
                                    'image' => $subCategoryImage
                                ];
                                if (isset($selectedFilters[$attributeCode])
                                    && in_array($subcategory['id'], $selectedFilters[$attributeCode])) {
                                    $filterValues[$j]['sub_categories'][$i]['selected'] = true;
                                }
                                $i++;
                            }
                        }
                        if (isset($selectedFilters[$attributeCode])
                            && in_array($item->getValue(), $selectedFilters[$attributeCode])) {
                            $filterValues[$j]['selected'] = true;
                        }
                        $j++;
                    }
                } else {
                    $attribute = null;
                    if ($filter->getRequestVar() != 'cat') {
                        $attribute = $filter->getAttributeModel();
                    }
                    $searchCriteriaAttribute = $layer->getProductCollection()->getSearchCriteria([$attributeCode]);
                    $attributeSearchResult = $this->search->search($searchCriteriaAttribute);
                    if ($attributeCode) {
                        $optionsFacetedData = $layer->getProductCollection()->getFacetedData(
                            $attributeCode,
                            $attributeSearchResult
                        );
                    }

                    $j = 0;
                    if (!empty($optionsFacetedData)) {
                        foreach ($optionsFacetedData as $option) {
                            $label = null;
                            if ($attribute) {
                                $label = $attribute->getSource()->getOptionText($option['value']);
                            }
                            if ($label) {
                                $label = str_replace('&amp;', '&', $label);
                                $filterValues[$j]['count'] = $option['count'];
                                $filterValues[$j]['label'] = ucwords(strtolower($label));
                                $filterValues[$j]['value'] = $option['value'];

                                if ($attributeType == 'swatch'
                                    && ($atributeSwatchHashcode = $this->getAtributeSwatchHashcode($option['value']))) {
                                    $filterValues[$j]['swatch_value'] = $atributeSwatchHashcode;
                                }
                                if (isset($selectedFilters[$attributeCode])
                                    && in_array($option['value'], $selectedFilters[$attributeCode])) {
                                    $filterValues[$j]['selected'] = true;
                                }
                                $j++;
                            }
                        }
                    }
                }
            }

            if ((!empty($filterValues)) || $attributeType == 'range') {
                if (count($filterValues) == 0
                    && !array_key_exists($attributeCode, $selectedFilters)
                    && !in_array($attributeCode, $ignoreFilters)) {
                    continue;
                }
                if ($attributeCode != self::PRICE) {
                    if ($attributeType == "range" && $from && $to) {
                        $data[] = [
                            "filter" => $filter,
                            "attribute_code" => $attributeCode,
                            "name" => strtoupper($availablefilter),
                            "options" => $filterValues,
                            "attribute_type" => $attributeType,
                            "selected_price" => ['min' => floatval($from), 'max' => floatval($to)]
                        ];
                    } else {
                        $data[] = [
                            "filter" => $filter,
                            "attribute_code" => $attributeCode,
                            "name" => strtoupper($availablefilter),
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

        $category = $this->categoryRepository->get($categoryId, $this->storeManager->getStore()->getId());
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
        $category = $this->categoryRepository->get($categoryId, $this->storeManager->getStore()->getId());
        if ($category->getThumbnailImage()) {
            $name = basename($category->getThumbnailImage());
            $path = 'catalog/category/' . $name;
            $categoryImage = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $path;
        }
        return $categoryImage;
    }

    /**
     * @param int $categoryId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getSubCategories($categoryId)
    {
        $category = $this->categoryRepository->get($categoryId, $this->storeManager->getStore()->getId());
        $subCategories = $category->getChildrenCategories();
        $subCategoriesData = [];
        foreach ($subCategories as $subCategory) {
            if ($subCategory->getIsActive() == 1) {
                $subCategoriesData[] = [
                    'name' => $subCategory->getName(),
                    'id' => $subCategory->getEntityId(),
                    'count' => $subCategory->getProductCount()
                ];
            }
        }
        return $subCategoriesData;
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
     * @inheritdoc
     */
    public function getProductListUpdated($categoryId, SearchCriteriaInterface $searchCriteria)
    {
        $this->addAvailableFilter = false;
        return $this->prepareCollectionResult($searchCriteria, $categoryId);
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param int|null $categoryId
     * @return ProductSearchResultsInterface
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function prepareCollectionResult(SearchCriteriaInterface $searchCriteria, $categoryId = null)
    {
        $childCatArray = [];
        if ($categoryId) {
            $childCatArray = $this->getChildCategories($categoryId);
        }
        /** @var ProductSearchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->getCollection($searchCriteria, $categoryId);
        if (!$collection) {
            return $searchResult;
        }

        $items = [];
        $totalItems = $collection->getSize();

        if ($totalItems) {
            $wishlistProductIds = null;
            if ($this->customerId) {
                $wishlistProductIds = $this->getWishlistByCustomerId($this->customerId);
            }
            $storeId = $this->storeManager->getStore()->getId();
            $this->addOtherData($collection, $searchCriteria);
            $items = $collection->getItems();
            /** @var \Magento\Catalog\Model\Product $product */
            foreach ($items as $key => $product) {
                $product->setData(
                    'image',
                    $this->getProductImageUrl($product, $storeId, 'product_page_main_image.')
                );
                $product->setData(
                    'small_image',
                    $this->getProductImageUrl(
                        $product,
                        $storeId,
                        'category_page_grid.',
                        'small_image'
                    )
                );

                if ($wishlistProductIds) {
                    $product->setData('wishlist_product', $wishlistProductIds);
                }
            }
        }
        $searchResult->setItems($items);
        $searchResult->setTotalCount($totalItems);
        $searchResult->setChildCategories($childCatArray);

        if (count($this->availableFilters) > 0) {
            $searchResult->setAvailableFilters($this->availableFilters);
        }

        return $searchResult;
    }

    /**
     * @param int $categoryId
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getChildCategories($categoryId)
    {
        $currentStore = $this->storeManager->getStore();
        $category = $this->categoryRepository->get($categoryId, $currentStore->getId());
        $subCategories = $category->getChildrenCategories()
            ->addAttributeToSelect('thumbnail_image')
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('include_in_menu', true);
        $childCategoriesData = [];
        foreach ($subCategories as $subCategory) {
            if ($currentStore->getCode() == self::STORE_CODE) {
                $imageUrl = "";
                if ($subCategory->getThumbnailImage()) {
                    $path = 'catalog/category/' . basename($subCategory->getThumbnailImage());
                    $imageUrl = $currentStore->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $path;
                }
                $childCategoriesData[] = [
                    'id' => $subCategory->getEntityId(),
                    'name' => $subCategory->getName(),
                    'image_url' => $imageUrl
                ];
            }
        }
        return $childCategoriesData;
    }

    /**
     * Retrieve product collection
     *
     * @param SearchCriteriaInterface $searchCriteria Search criteria
     * @param int|null $categoryId
     * @param bool $removeAdditionalFilter
     * @return Collection
     *
     * @throws LocalizedException
     */
    public function getCollection(
        SearchCriteriaInterface $searchCriteria,
        $categoryId = null,
        $removeAdditionalFilter = false,
        $wishlistProducts = false
    ) {
        $catFilterApplied = false;
        $skuFilterApplied = false;
        $categoryId = $categoryId ?? $this->storeManager->getStore()->getRootCategoryId();
        $searchLayer = false;
        $searchCriteria = $this->filterSearchCriteria($searchCriteria);

        $showAllProducts = $this->requestApi->getParam('showAllProducts');

        if ($wishlistProducts) {
            $productCollection = $this->collectionFactory->create();
            $productCollection = $this->getFilteredCollection($productCollection, $categoryId);
            return $this->addAttributesAndSort($productCollection, $searchCriteria);
        }

        if ($this->entityIds && $categoryId == $this->storeManager->getStore()->getRootCategoryId()) {
            $productCollection = $this->collectionFactory->create();
            $productCollection = $this->getFilteredCollection($productCollection, $categoryId);
            $productCollection->addAttributeToFilter('entity_id', ['in' => $this->entityIds]);
            return $this->addAttributesAndSort($productCollection, $searchCriteria);
        }
        if ($showAllProducts) {
            $productCollection = $this->collectionFactory->create();
            if ($categoryId != $this->storeManager->getStore()->getRootCategoryId()) {
                $productCollection->addCategoriesFilter(['in' => [$categoryId]]);
            }
            if ($this->skus) {
                $productCollection->addAttributeToFilter('sku', ['in' => $this->skus]);
            }
            return $this->addAttributesAndSort($productCollection, $searchCriteria);
        } else {
            $storeId = $this->storeManager->getStore()->getId();
            $filterGroups = $searchCriteria->getFilterGroups();

            foreach ($filterGroups as $filters) {
                foreach ($filters->getFilters() as $filter) {
                    if ($filter->getField() == "search_term") {
                        $searchLayer = true;
                        break;
                    }
                }
            }

            if ($categoryId == $this->storeManager->getStore()->getRootCategoryId() && $searchLayer) {
                $layer = $this->searchLayer;
            } else {
                $layer = $this->layerResolver->get();
            }
            $layer->setCurrentCategory($categoryId);
            $productCollection = $layer->getProductCollection();
            $productCollection = $this->getFilteredCollection($productCollection, $categoryId);
            $from = $to = "";

            foreach ($filterGroups as $filters) {
                foreach ($filters->getFilters() as $filter) {
                    if ($filter->getField() == "customer_id") {
                        continue;
                    }
                    if ($filter->getField() == "search_term") {
                        $productCollection->addSearchFilter($filter->getValue());
                        continue;
                    }
                    $filterValueArray = explode(",", $filter->getValue());
                    if ($filter->getField() == "category_id") {
                        $productCollection->addCategoriesFilter(['in' => $filterValueArray]);
                        $catFilterApplied = true;
                        continue;
                    }
                    $selectedFilters[$filter->getField()] = explode(',', $filter->getValue());
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
                $productCollection->addFieldToFilter(
                    'price',
                    ['from' => $from, 'to' => $to]
                );
            }

            if ($removeAdditionalFilter == false) {
                if ($this->entityIds) {
                    $productCollection->addAttributeToFilter('entity_id', ['in' => $this->entityIds]);
                }
                if ($this->skus) {
                    $skuFilterApplied = true;
                    $productCollection->addAttributeToFilter('sku', ['in' => $this->skus]);
                }
            }

            if ($searchLayer || $catFilterApplied) {
                $productIds = [];
                $productCollection->getSelect()->reset(\Zend_Db_Select::COLUMNS);
                $productCollection->getSelect()->columns('entity_id');
                $productIds = $productCollection->getData();
            } else {
                $productIds = $productCollection->getAllIds();
            }

            $productCollection = $this->getFilteredCollection($productCollection, $categoryId);
            if ($from && $to) {
                $productCollection->getSelect()->where('price_index.min_price > ' . $from);
                $productCollection->getSelect()->where('price_index.min_price <= ' . $to);
            }

            $this->addAttributesAndSort($productCollection, $searchCriteria);

            if (!$searchLayer && !$catFilterApplied) {
                $this->collectionProcessor->process($searchCriteria, $productCollection);
            }
            return $productCollection;
        }
    }

    /**
     * Function is used to get login customer wishlist product collection.
     *
     * @param int $customerId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getWishlistByCustomerId($customerId)
    {
        $wishlist = $this->wishlistFactory->create()->loadByCustomerId($customerId)->getItemCollection();
        return $wishlist->getColumnValues('product_id');
    }

    /**
     * @param $product
     * @param $storeId
     * @param string $imageId
     * @param string $imageType
     *
     * @return string
     */
    public function getProductImageUrl(
        $product,
        $storeId,
        $imageId = 'image',
        $imageType = 'image'
    ) {
        $this->appEmulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);

        $url = $this->placeHolderImage->create()
            ->init($product, $imageId, ['type' => $imageType])
            ->getUrl();

        $this->appEmulation->stopEnvironmentEmulation();

        return $url;
    }
    /**
     * Reset filters
     *
     * @param SearchCriteriaInterface $searchCriteria search criteria
     *
     * @return SearchCriteriaInterface
     */
    protected function filterSearchCriteria(
        SearchCriteriaInterface $searchCriteria
    ) {
        /** @var FilterGroup $filterGroups */
        $filterGroups = $searchCriteria->getFilterGroups();
        foreach ($filterGroups as $key => $filterGroup) {
            $filters = $filterGroup->getFilters();
            foreach ($filters as $fKey => $filter) {
                switch ($filter->getField()) {
                    case 'entity_id':
                        $this->entityIds = explode(',', $filter->getValue() ?? '');
                        unset($filters[$fKey]);
                        break;
                    case 'sku':
                        $this->skus = explode(',', $filter->getValue() ?? '');
                        unset($filters[$fKey]);
                        break;
                    case 'customer_id':
                        $this->customerId = $filter->getValue();
                        unset($filters[$fKey]);
                        break;
                    case 'product_list_order':
                        $this->product_list_order = $filter->getValue();
                        $this->product_list_order_condition_type =
                            ($filter->getConditionType() == 'asc' || $filter->getConditionType() == 'desc')
                                ? $filter->getConditionType() : false;
                        unset($filters[$fKey]);
                        break;
                }
            }
            $filterGroup->setFilters($filters);
        }
        $searchCriteria->setFilterGroups($filterGroups);
        return $searchCriteria;
    }

    /**
     * @param $productCollection
     * @param $categoryId
     * @return mixed
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getFilteredCollection($productCollection, $categoryId)
    {
        //Filter the collection with the category and inventory

        $storeId = $this->storeManager->getStore()->getId();
        $productCollection->getSelect()
            ->join(
                ['cat_index' => 'catalog_category_product_index_store' . $storeId],
                "e.entity_id = cat_index.product_id AND cat_index.store_id = " . $storeId .
                " AND cat_index.category_id = " . $categoryId,
                ['*']
            )
            ->join(['IS' => "inventory_stock_{$this->getStock()->getStockId()}"], 'IS.sku = e.sku', [])
            ->where('cat_index.visibility > 0');

        return $productCollection;
    }

    /**
     * @param $productCollection
     * @param $searchCriteria
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function addAttributesAndSort($productCollection, $searchCriteria)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $productCollection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents();

        //sort Order
        $defaultSort = $this->scopeConfig->getValue(self::DEFAULT_SORT_BY, ScopeInterface::SCOPE_STORE, $storeId);
        $sortOrders = $searchCriteria->getSortOrders();

        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $sortAttribute = $sortOrder->getField();
                $direction = $sortOrder->getDirection();
                break;
            }
            if (isset($sortAttribute) && isset($direction)) {
                if ($sortAttribute == self::CREATED_AT_SORT) {
                    $productCollection->getSelect()->order('e.created_at ' . $direction);
                } else {
                    $productCollection->setOrder($sortAttribute, $direction);
                }
            }
        } else {
            $productCollection->setOrder($defaultSort, self::DEFAULT_DIRECTION);
        }

        $pageSize = $searchCriteria->getPageSize();
        if ($pageSize) {
            $productCollection->setPageSize($pageSize);
        }
        $currentPage = $searchCriteria->getCurrentPage();
        if ($currentPage) {
            $productCollection->setCurPage($currentPage);
        }

        return $productCollection;
    }

    /**
     * Retrieve collection processor
     *
     * @return CollectionProcessorInterface
     * @deprecated 102.0.0
     */
    protected function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = ObjectManager::getInstance()->get(
                'Magento\Catalog\Model\Api\SearchCriteria\ProductCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }

    /**
     * Add other data to collection
     *
     * @param Collection $collection collection
     * @param SearchCriteriaInterface $searchCriteria
     * @return void
     */
    protected function addOtherData($collection, SearchCriteriaInterface $searchCriteria)
    {
        $sortOrders = $searchCriteria->getSortOrders();
        $orders = [];
        $direction = null;
        if ($sortOrders) {
            foreach ($sortOrders as $order) {
                $orders[] = $order->getField();
                if ($order->getField() == self::PRICE) {
                    $direction = $order->getDirection();
                }
            }
        }
        if (in_array('position', $orders) || !$sortOrders && is_null($this->product_list_order)) {
            $showAllProducts = $this->requestApi->getParam('showAllProducts');
            if (!$showAllProducts) {
                $collection->getSelect()->order('cat_index.position ' . \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
            }
            $collection->getSelect()->order('e.entity_id ' . \Magento\Framework\Data\Collection::SORT_ORDER_DESC);
        }

        if (in_array('price', $orders)) {
            $collection->setOrder('price', $direction);
        }

        $collection->setPageSize($searchCriteria->getPageSize())
            ->setCurPage($searchCriteria->getCurrentPage())
            ->load();
        $collection->addCategoryIds();
        $this->addExtensionAttributes($collection);
    }

    /**
     * @return StockInterface
     * @throws LocalizedException
     */
    public function getStock()
    {
        return $this->stockByWebsiteIdResolver->execute(
            $this->storeManager->getWebsite()->getId()
        );
    }
    /**
     * Add extension attributes to loaded items.
     *
     * @param Collection $collection
     * @return Collection
     */
    protected function addExtensionAttributes(Collection $collection): Collection
    {
        foreach ($collection->getItems() as $item) {
            $this->readExtensions->execute($item);
        }
        return $collection;
    }
}
