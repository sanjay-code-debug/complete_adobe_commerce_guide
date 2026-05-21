<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */
namespace Codilar\CustomApi\Model;

use Klevu\Categorynavigation\Helper\Data as KlevuCatNavHelperData;
use Klevu\Categorynavigation\Model\Api\Action\CatnavIdsearch as KlevuCatnavApiIdsearch;
use Klevu\Logger\Constants as LoggerConstants;
use Klevu\Search\Helper\Config as KlevuHelperConfig;
use Klevu\Search\Helper\Data as KlevuHelperData;
use Magento\Catalog\Model\Category as Category_Model;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\CategoryFactory as Magento_CategoryFactory;
use Magento\Store\Model\StoreManagerInterface as Magento_StoreManager;
use Psr\Log\LoggerInterface as PsrLogger;

class Relevance
{

    /**
     * @var PsrLogger
     */
    protected PsrLogger $logger;

    protected $_klevu_type_of_records = 'KLEVU_PRODUCT';

    /**
     * Constructor
     *
     * @param PsrLogger $logger
     * @param KlevuHelperConfig $searchHelperConfig
     * @param KlevuCatNavHelperData $categorynavigationHelperConfig
     * @param KlevuHelperData $searchHelperData
     * @param KlevuCatnavApiIdsearch $apiActionIdsearch
     * @param Category_Model $categoryModel
     * @param Magento_StoreManager $storeManager
     * @param Magento_CategoryFactory $categoryFactory
     */
    public function __construct(
        PsrLogger $logger,
        KlevuHelperConfig $searchHelperConfig,
        KlevuCatNavHelperData $categorynavigationHelperConfig,
        KlevuHelperData $searchHelperData,
        KlevuCatnavApiIdsearch $apiActionIdsearch,
        Category_Model $categoryModel,
        Magento_StoreManager $storeManager,
        CategoryFactory $categoryFactory
    ) {
        $this->logger = $logger;
        $this->_searchHelperConfig = $searchHelperConfig;
        $this->_searchHelperData = $searchHelperData;
        $this->_apiActionIdsearch = $apiActionIdsearch;
        $this->_categoryModel = $categoryModel;
        $this->_categorynavigationHelperConfig = $categorynavigationHelperConfig;
        $this->_storeManager = $storeManager;
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Get ids
     *
     * @param int $categoryId
     * @return mixed
     */
    public function getSearchFilters($categoryId)
    {
        $currentCategory = $this->categoryFactory->create()->load($categoryId);
        $categoryNames = [];
        $parentnames = [];
        try {
            if (!$currentCategory instanceof Category_Model) {
                return false;
            }
            foreach ($currentCategory->getParentCategories() as $parent) {
                $parentnames[] = $parent->getName();
            }
            $allCategoryNames = implode(";", $parentnames);

            $pathIds = [];
            $pathIds = $currentCategory->getPathIds();
            if (!empty($pathIds)) {
                unset($pathIds[0]);
                unset($pathIds[1]);
                foreach ($pathIds as $key => $value) {
                    $catname = $this->_categoryModel->clearInstance()
                        ->setStoreId($this->_storeManager->getStore()->getId())->load($value)->getName();
                    $categoryNames[] = $catname;
                    $this->_searchHelperData
                        ->log(LoggerConstants::ZEND_LOG_CRIT, sprintf("Category Name %s", $catname));
                }
                $allCategoryNames = implode(";", $categoryNames);
            }
            $category = $this->_klevu_type_of_records . " " . $allCategoryNames;
            if ($this->_categorynavigationHelperConfig->getNoOfResults()) {
                $noOfResults = $this->_categorynavigationHelperConfig->getNoOfResults();
            } else {
                $noOfResults = 2000;
            }
            if (empty($this->_klevu_parameters)) {
                $this->_klevu_parameters = [
                    'ticket' => $this->_searchHelperConfig->getJsApiKey() ,
                    'noOfResults' => $noOfResults,
                    'term' => '*',
                    'paginationStartsFrom' => 0,
                    'enableFilters' => 'false',
                    'klevuShowOutOfStockProducts' => 'true',
                    'isCategoryNavigationRequest' => 'true',
                    'category' => $category,
                    'categoryIds' => $currentCategory->getId(),
                    'visibility' => 'catalog'
                ];
            }
            $productIds = [];
            $data = $this->_apiActionIdsearch->execute($this->_klevu_parameters);
            if (isset($data->getData()['result'])){
                $arrData = $data->getData()['result'];
                if (isset($arrData['id']) && count($arrData) == 2) {
                    $productIds[] = $arrData['id'];
                } else {
                    foreach ($arrData as $arr) {
                        $productIds[] = $arr['id'];
                    }
                }
            }
            return $productIds;
        } catch (\Exception $e) {
            $this->_searchHelperData->log(
                LoggerConstants::ZEND_LOG_CRIT,
                sprintf(
                    "Category API Exception thrown in %s::%s - %s",
                    __CLASS__,
                    __METHOD__,
                    $e->getMessage()
                )
            );
        }
    }
}
