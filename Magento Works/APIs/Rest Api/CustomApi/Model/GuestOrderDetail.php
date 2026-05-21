<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\GuestOrderDetailInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Helper\Guest;
use Magento\Sales\Api\OrderRepositoryInterface as CoreOrderRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Store\Model\StoreManagerInterface;

class GuestOrderDetail implements GuestOrderDetailInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;
    /**
     * @var CoreOrderRepository
     */
    protected CoreOrderRepository $coreOrderRepositoryInterface;
    /**
     * @var SearchResultFactory
     */
    protected SearchResultFactory $searchResultFactory;

    /**
     * @var Guest
     */
    protected Guest $guestOrder;

    /**
     * @var SearchCriteriaBuilder
     */
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @param SearchResultFactory $searchResultFactory
     * @param Guest $guestOrder
     * @param CoreOrderRepository $coreOrderRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        SearchResultFactory $searchResultFactory,
        Guest $guestOrder,
        CoreOrderRepository $coreOrderRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager
    ) {
        $this->searchResultFactory = $searchResultFactory;
        $this->guestOrder = $guestOrder;
        $this->coreOrderRepositoryInterface = $coreOrderRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     * @throws NoSuchEntityException
     */
    public function getOrders(SearchCriteriaInterface $searchCriteria)
    {
        $email = "";
        $lastName = "";
        $orderIncrementId = "";
        $storeId = $this->storeManager->getStore()->getId();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() == 'increment_id') {
                    $orderIncrementId = trim($filter->getValue());
                }
                if ($filter->getField() == 'customer_email') {
                    $email = trim($filter->getValue());
                }
                if ($filter->getField() == 'billing_lastname') {
                    $lastName = trim($filter->getValue());
                }
            }
        }

        $order = $this->coreOrderRepositoryInterface->getList($this->searchCriteriaBuilder
            ->addFilter('increment_id', $orderIncrementId)
            ->addFilter('customer_email', $email)
            ->addFilter('store_id', $storeId)
            ->create());

        if ($order->getTotalCount() > 0) {
            $item = $order->getFirstItem();
            $billingLastName = $item->getBillingAddress()->getLastName();
            $billingEmail = $item->getBillingAddress()->getEmail();
            if (
                strtolower($lastName) === strtolower($billingLastName)
                && strtolower($email) === strtolower($billingEmail)
            ) {
                return $order;
            } else {
                // Return the empty order - when billing last name is not same
                return $this->coreOrderRepositoryInterface->getList($this->searchCriteriaBuilder
                    ->addFilter('customer_email', '')
                    ->create());
            }
        } else {
            // Return the empty order
            return $order;
        }
    }
}
