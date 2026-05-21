<?php

namespace Codilar\CustomApi\Model\Helper;

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\Search\FilterGroupBuilderFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use WebPanda\ProductWarning\Model\WarningFactory;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\InventorySalesApi\Model\StockByWebsiteIdResolverInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Psr\Log\LoggerInterface as PsrLogger;

class Data
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;

    /**
     * @var FilterGroupBuilderFactory
     */
    private FilterGroupBuilderFactory $filterGroupBuilderFactory;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var TimezoneInterface
     */
    private TimezoneInterface $timezone;

    /**
     * @var WarningFactory
     */
    private WarningFactory $warningFactory;

    /**
     * @var FilterProvider
     */
    private FilterProvider $filterProvider;

    /**
     * @var StockByWebsiteIdResolverInterface
     */
    private StockByWebsiteIdResolverInterface $stockByWebsiteId;

    /**
     * @var GetProductSalableQtyInterface
     */
    private GetProductSalableQtyInterface $getProductSalableQty;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * Get config delivery and return message value
     */
    public const DELIVERY_RETURN_MESSAGE = "footer_config/deliver_return_message/message_ios";

    /**
     * @var PsrLogger
     */
    private PsrLogger $logger;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param FilterGroupBuilderFactory $filterGroupBuilderFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param TimezoneInterface $timezone
     * @param WarningFactory $warningFactory
     * @param FilterProvider $filterProvider
     * @param StockByWebsiteIdResolverInterface $stockByWebsiteId
     * @param GetProductSalableQtyInterface $getProductSalableQty
     * @param CategoryRepositoryInterface $categoryRepository
     * @param DateTime $dateTime
     * @param PsrLogger $logger
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        FilterGroupBuilderFactory $filterGroupBuilderFactory,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        TimezoneInterface $timezone,
        WarningFactory $warningFactory,
        FilterProvider $filterProvider,
        StockByWebsiteIdResolverInterface $stockByWebsiteId,
        GetProductSalableQtyInterface $getProductSalableQty,
        CategoryRepositoryInterface $categoryRepository,
        DateTime $dateTime,
        PsrLogger $logger
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->filterGroupBuilderFactory = $filterGroupBuilderFactory;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->timezone = $timezone;
        $this->warningFactory = $warningFactory;
        $this->filterProvider = $filterProvider;
        $this->stockByWebsiteId = $stockByWebsiteId;
        $this->getProductSalableQty = $getProductSalableQty;
        $this->categoryRepository = $categoryRepository;
        $this->dateTime = $dateTime;
        $this->logger = $logger;
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
     * @param $path
     * @param $scope
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getConfigurationValue($path, $scope)
    {
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        return $this->scopeConfig->getValue(
            $path,
            $scope,
            $websiteId
        );
    }

    /**
     * Get current store date
     *
     * @return string
     */
    public function getCurrentStoreDate()
    {
        // Get store timezone
        $storeTimezone = $this->timezone->getConfigTimezone();
        // Get current date in store timezone
        return $this->timezone->date(new \DateTime('now', new \DateTimeZone($storeTimezone)));
    }

    /**
     * Get wp_warning message in cart page
     *
     * @return mixed
     */
    public function getCartItemWarningMessage($attributeCodeValue) {
        if (empty($attributeCodeValue)) {
            return null;
        }
        $warningObject = $this->warningFactory->create()->load($attributeCodeValue);
        $isEnabled = $this->filterProvider->getPageFilter()->filter($warningObject->getShowInCart());
        if ($isEnabled) {
            $value =  $this->filterProvider->getPageFilter()->filter($warningObject->getCartItemMessage());
            if ($value == ""){
                return null;
            } else {
                return $value;
            }
        }
        return null;
    }

    /**
     * Check qty left
     *
     * @param $product
     * @return float|int|void
     */
    public function getQtyLeft($product)
    {
        try {
            $leftQty =  $this->getOnlyQtyLeftData($product);
            return $leftQty ?? 0;
        } catch (Exception $e) {
            $this->logger->error(' PDP page error - ' . $e->getMessage());
        }
    }

    /**
     * Check quantity left logic
     *
     * @param $product
     * @return float|void|null
     */

    public function getOnlyQtyLeftData($product)
    {
        try {
            $sku = $product->getSku();
            $websiteId = (int)$product->getStore()->getWebsiteId();
            $availableQty = null;
            $getOnlyQtyLeft = null;

            if (($product->getCategoryIds()) && ($product->getTypeId() == "simple")) {
                $stockId = (int)$this->stockByWebsiteId->execute($websiteId)->getStockId();
                $productSalableQty = $this->getProductSalableQty->execute($sku, $stockId);

                if (count($product->getCategoryIds()) > 1) {
                    foreach ($product->getCategoryIds() as $categories) {
                        $category = $this->categoryRepository->get($categories);
                        if ($category->getLevel() >= 3) {
                            if (is_null($category->getOnlyQtyLeft())) {
                                $parentCategory = $category->getParentCategory();
                                if (is_null($parentCategory->getOnlyQtyLeft())) {
                                    $currentCategory = $this->categoryRepository->get($parentCategory->getParentId());
                                    if (!is_null($currentCategory->getOnlyQtyLeft())) {
                                        $getOnlyQtyLeft = $currentCategory->getOnlyQtyLeft();
                                    }
                                } else {
                                    $getOnlyQtyLeft = $parentCategory->getOnlyQtyLeft();
                                }
                            } else {
                                $getOnlyQtyLeft = $category->getOnlyQtyLeft();
                            }
                        } elseif ($category->getLevel() == 2) {
                            if ($category->getOnlyQtyLeft()) {
                                $getOnlyQtyLeft = $category->getOnlyQtyLeft();
                            }
                        }
                    }
                } else {
                    $id = $product->getCategoryIds();
                    $convertToInt = (int)$id[0];
                    $category = $this->categoryRepository->get($convertToInt);
                    if ($category->getLevel() >= 3) {
                        if (is_null($category->getOnlyQtyLeft())) {
                            $parentCategory = $category->getParentCategory();
                            if (is_null($parentCategory->getOnlyQtyLeft())) {
                                $currentCategory = $this->categoryRepository->get($parentCategory->getParentId());
                                if (!is_null($currentCategory->getOnlyQtyLeft())) {
                                    $getOnlyQtyLeft = $currentCategory->getOnlyQtyLeft();
                                }
                            } else {
                                $getOnlyQtyLeft = $parentCategory->getOnlyQtyLeft();
                            }
                        } else {
                            $getOnlyQtyLeft = $category->getOnlyQtyLeft();
                        }
                    } elseif ($category->getLevel() == 2) {
                        if ($category->getOnlyQtyLeft()) {
                            $getOnlyQtyLeft = $category->getOnlyQtyLeft();
                        }
                    }
                }
                if ($getOnlyQtyLeft && $getOnlyQtyLeft !== 0) {
                    if ($productSalableQty > 0 && $productSalableQty <= $getOnlyQtyLeft) {
                        $availableQty = $productSalableQty;
                    }
                }
            }
            return $availableQty;
        } catch (Exception $e) {
            $this->logger->error(' PDP page error - ' . $e->getMessage());
        }
    }

    /**
     * Wishlist special price
     *
     * @param $specialPrice
     * @param $special_from_date
     * @param $special_to_date
     * @return int|mixed
     * @throws Exception
     */
    public function specialPriceWislist($specialPrice, $special_from_date, $special_to_date)
    {
        $current_date = $this->dateTime->date();
        // Check if $special_from_date
        if ((!empty($specialPrice))) {
            if (empty($special_from_date) && empty($special_to_date)) {
                return $specialPrice;
            } else {
                // Convert date strings to DateTime objects
                $currentDate = new \DateTime($current_date);
                $specialFromDate = new \DateTime($special_from_date);
                $specialToDate = new \DateTime($special_to_date);
                // Check if currentDate is within the special date range and specialPrice is not empty
                if ($currentDate >= $specialFromDate && $currentDate <= $specialToDate) {
                    return $specialPrice;
                }
            }
        }
        return 0;
    }

    /**
     *
     * Get attribute value using attribute code
     *
     * @param $product
     * @return mixed|null
     */
    public function getProductWarningMessage($product)
    {
        $wpWarningValue = $product->getCustomAttribute('wp_warning')->getValue();
        if (empty($wpWarningValue)) {
            return null;
        } else {
            return $wpWarningValue;
        }
    }

    /**
     * Get pop up message
     *
     * @param $attributeCodeValue
     * @return mixed
     * @throws Exception
     */
    public function getMessageUsingAttributeId($attributeCodeValue)
    {
        $getMessage = $this->scopeConfig->getValue(
            self::DELIVERY_RETURN_MESSAGE,
            ScopeInterface::SCOPE_STORE
        );

        if (!empty($attributeCodeValue)) {
            $warningObject = $this->warningFactory->create()->load($attributeCodeValue);
            $value = $this->filterProvider->getPageFilter()->filter($warningObject->getIosPopupMessage());
            $startPos = strpos($value, '>');
            $endPos = strrpos($value, '<');
            // Remove the surrounding <div> tags and their attributes
            if ($startPos !== false && $endPos !== false) {
                $value = substr($value, $startPos + 1, $endPos - $startPos - 1);
            }
            if (empty($getMessage)) {
                return $value;
            }
            return $getMessage . (!empty($value) ? $value : '');
        }
        return $getMessage;
    }

    /**
     * @param $attributeCodeValue
     * @return string|void
     * @throws Exception
     */
    public function getMessageUsingAttributeIdWithOutDeliveryMessage($attributeCodeValue)
    {
        if (!empty($attributeCodeValue)) {
            $warningObject = $this->warningFactory->create()->load($attributeCodeValue);
            $value = $this->filterProvider->getPageFilter()->filter($warningObject->getPopupMessage());
            $startPos = strpos($value, '>');
            $endPos = strrpos($value, '<');
            // Remove the surrounding <div> tags and their attributes
            if ($startPos !== false && $endPos !== false) {
                $value = substr($value, $startPos + 1, $endPos - $startPos - 1);
            }
            return (!empty($value) ? $value : '');
        }
    }

    /**
     * @return string
     */
    public function getServerTime()
    {
        date_default_timezone_set('Asia/Dubai'); // GST (Gulf Standard Time)
        $info = getdate();
        $date = sprintf("%04d-%02d-%02d", $info['year'], $info['mon'], $info['mday']);
        $time = sprintf("%02d:%02d:%02d.%03dZ", $info['hours'], $info['minutes'], $info['seconds'], 0);
        return $date . 'T' . $time;
    }
}
