<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model\Helper;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\InventorySales\Model\ResourceModel\GetAssignedStockIdForWebsite;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;

class GetSalableQty
{

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var StoreRepositoryInterface
     */
    protected StoreRepositoryInterface $storeRepository;

    /**
     * @var WebsiteRepositoryInterface
     */
    protected WebsiteRepositoryInterface $websiteRepository;

    /**
     * @var GetAssignedStockIdForWebsite
     */
    protected GetAssignedStockIdForWebsite $assignedStockId;

    /**
     * @var GetProductSalableQtyInterface
     */
    protected GetProductSalableQtyInterface $productSalebleQty;

    /**
     * @param StoreManagerInterface $storeManager
     * @param StoreRepositoryInterface $storeRepository
     * @param WebsiteRepositoryInterface $websiteRepository
     * @param GetAssignedStockIdForWebsite $assignedStockId
     * @param GetProductSalableQtyInterface $productSalableQty
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        StoreRepositoryInterface $storeRepository,
        WebsiteRepositoryInterface $websiteRepository,
        GetAssignedStockIdForWebsite $assignedStockId,
        GetProductSalableQtyInterface $productSalableQty
    ) {
        $this->storeManager = $storeManager;
        $this->storeRepository = $storeRepository;
        $this->websiteRepository = $websiteRepository;
        $this->assignedStockId = $assignedStockId;
        $this->productSalebleQty = $productSalableQty;
    }

    /**
     * Get salable qty
     *
     * @return float
     * @throws NoSuchEntityException
     */
    public function getSalableQty($sku): ?float
    {
        $websiteCode = $this->getWebsiteCode($this->storeManager->getStore()->getId());
        $stockId = $this->assignedStockId->execute($websiteCode);
        // Check if websiteCode is "admin" and stockId is null
        if ($websiteCode === "admin" && $stockId === null) {
            return 0; // Skip the execute method call
        }
        $scalableQty = $this->productSalebleQty->execute($sku, $stockId);
        return !empty($scalableQty) && $scalableQty >= 0 ? $scalableQty : 0;
    }

    /**
     * Get website id
     *
     * @param $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getWebsiteCode($storeId)
    {
        $store = $this->storeRepository->getById($storeId);
        $website = $this->websiteRepository->getById($store->getWebsiteId());
        return $website->getCode();
    }
}
