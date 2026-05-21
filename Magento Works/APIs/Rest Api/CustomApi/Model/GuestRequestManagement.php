<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2024 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\GuestRequestManagementInterface;
use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\RMA\Api\Data\RequestReplyInterface;
use Mageplaza\RMA\Api\SearchResult\RequestSearchResultInterface;
use Mageplaza\RMA\Api\SearchResult\RequestSearchResultInterfaceFactory;
use Mageplaza\RMA\Helper\Conversation;
use Mageplaza\RMA\Helper\Image;
use Mageplaza\RMA\Model\Request;
use Mageplaza\RMA\Model\ResourceModel\Request\Collection as RequestCollection;
use Mageplaza\RMA\Model\ResourceModel\Request\CollectionFactory;
use Mageplaza\RMA\Model\ResourceModel\Request\Collection;
use Mageplaza\RMA\Model\Api\AbstractManagement;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Mageplaza\RMA\Model\ResourceModel\Rule\Collection as RuleCollection;
use Mageplaza\RMA\Model\ResourceModel\ShippingLabel\Collection as ShippingLabelCollection;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Mageplaza\RMA\Helper\Data as HelperData;
use Mageplaza\RMA\Model\Request\ReplyFactory;
use Mageplaza\RMA\Model\ResourceModel\Request\Reply as ReplyResource;
use Mageplaza\RMA\Model\RequestFactory;
use Mageplaza\RMA\Model\ResourceModel\Request as RequestResource;
use Mageplaza\RMA\Block\Customer\Request as BlockRequest;
use Psr\Log\LoggerInterface as PsrLogger;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class GuestRequestManagement implements GuestRequestManagementInterface
{

    /**
     * @var RequestSearchResultInterfaceFactory
     */
    private RequestSearchResultInterfaceFactory $requestSearchResultFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var AbstractManagement
     */
    private AbstractManagement $abstractManagement;

    /**
     * @var OrderCollectionFactory
     */
    private OrderCollectionFactory $orderCollectionFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var HelperData
     */
    private HelperData $helperData;

    /**
     * @var ReplyFactory
     */
    private ReplyFactory $replyFactory;

    /**
     * @var ReplyResource
     */
    private ReplyResource $replyResource;

    /**
     * @var RequestFactory
     */
    private RequestFactory $requestFactory;

    /**
     * @var RequestResource
     */
    private RequestResource $requestResource;

    /**
     * @var BlockRequest
     */
    private BlockRequest $blockRequest;

    /**
     * @var PsrLogger
     */
    private PsrLogger $logger;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param RequestSearchResultInterfaceFactory $requestSearchResultFactory
     * @param CollectionFactory $collectionFactory
     * @param AbstractManagement $abstractManagement
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CollectionProcessorInterface $collectionProcessor
     * @param HelperData $helperData
     * @param ReplyFactory $replyFactory
     * @param ReplyResource $replyResource
     * @param RequestFactory $requestFactory
     * @param RequestResource $requestResource
     * @param BlockRequest $blockRequest
     * @param PsrLogger $logger
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        RequestSearchResultInterfaceFactory $requestSearchResultFactory,
        CollectionFactory $collectionFactory,
        AbstractManagement $abstractManagement,
        OrderCollectionFactory $orderCollectionFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionProcessorInterface $collectionProcessor,
        HelperData $helperData,
        ReplyFactory $replyFactory,
        ReplyResource $replyResource,
        RequestFactory $requestFactory,
        RequestResource $requestResource,
        BlockRequest $blockRequest,
        PsrLogger $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->requestSearchResultFactory = $requestSearchResultFactory;
        $this->collectionFactory = $collectionFactory;
        $this->abstractManagement = $abstractManagement;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->helperData = $helperData;
        $this->replyFactory = $replyFactory;
        $this->replyResource = $replyResource;
        $this->requestFactory = $requestFactory;
        $this->requestResource = $requestResource;
        $this->blockRequest = $blockRequest;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function getGuestRequest(SearchCriteriaInterface $searchCriteria = null)
    {
        try {
            $orderId = "";
            foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
                foreach ($filterGroup->getFilters() as $filter) {
                    if ($filter->getField() == 'order_id') {
                        $orderId = $filter->getValue();
                    }
                }
            }
            return $this->getReturnData($searchCriteria, $orderId);
        } catch (Exception $e) {
            $this->logger->error("guest reply error" . $e->getMessage());
            throw new LocalizedException(__('wrong order id given .'));
        }
    }
    /**
     * @inheritdoc
     */
    public function saveGuestReply(RequestReplyInterface $reply)
    {
        try {
            $this->abstractManagement->checkEnabled();
            $this->abstractManagement->checkRequired([Request::REQUEST_ID], $reply->getData());
            $replyModel = $this->replyFactory->create();
            $reply->setType(Conversation::TYPE_CUSTOMER_RESPONSE);
            if ($reply->getUpload()) {
                $reply->setFiles($this->helperData->uploadMultiFiles($reply->getUpload(), 'reply'));
            }
            $result = $this->abstractManagement->saveEntity(
                $reply,
                $replyModel,
                'replyCustomer',
                $this->replyResource
            );
            $this->saveLastRequest($result);
            return $result;
        } catch (Exception $e) {
            $this->logger->error("guest reply error" . $e->getMessage());
            throw new LocalizedException(__('An error occurred while saving the guest reply.'));
        }
    }
    /**
     * @inheritdoc
     */
    public function cancel(RequestReplyInterface $reply)
    {
        try {
            $this->abstractManagement->checkEnabled();
            $this->abstractManagement->checkRequired([Request::REQUEST_ID], $reply->getData());
            $replyModel = $this->replyFactory->create();
            $request = $this->requestFactory->create();
            $this->requestResource->load($request, $reply->getRequestId());

            if (!$this->blockRequest->canCancelRequest($request)) {
                $this->logger->error("This request cannot be canceled.");
                throw new InputException(__('This request cannot be canceled.'));
            }

            if ($request->getIsCanceled() === '1') {
                $this->logger->error("The request has canceled.");
                throw new InputException(__('The request has canceled.'));
            }

            $reply->setAuthorName(__('Customer'));
            $reply->setType(Conversation::TYPE_CUSTOMER_RESPONSE);
            $reply->setIsVisibleOnFront(1);
            $reply->setContent(__('Customer has been canceled this request.'));

            $this->abstractManagement->saveEntity(
                $reply,
                $replyModel,
                'cancel',
                $this->replyResource
            );

            $request->setIsCanceled(1);
            $this->requestResource->save($request);

            return true;
        } catch (Exception $e) {
            $this->logger->error("An error occurred while canceling the request" . $e->getMessage());
            throw new LocalizedException(__('An error occurred while canceling the request.'));
        }
    }

    /**
     * Get the list of entities based on the provided criteria and guest order ID.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param string $guestOrderId
     * @return RequestSearchResultInterface
     * @throws LocalizedException
     */
    private function getReturnData(SearchCriteriaInterface $searchCriteria, $guestOrderId)
    {
        try {
            /** @var RequestSearchResultInterface $searchResult */
            $searchResult = $this->requestSearchResultFactory->create();

            /** @var OrderCollection $orderCollection */
            $orderCollection = $this->orderCollectionFactory->create()
                ->addAttributeToSelect('entity_id')
                ->addAttributeToFilter('entity_id', $guestOrderId);

            $orderIds = [];
            foreach ($orderCollection->getData() as $item) {
                $orderIds[] = $item['entity_id'];
            }

            if ($guestOrderId) {
                /** @var Collection $ruleCollection */
                $ruleCollection = $this->collectionFactory->create()->addFieldToFilter('order_id', ['in' => $orderIds]);
            } else {
                $this->logger->error("Guest order ID must be provided.");
                throw new \InvalidArgumentException("Guest order ID must be provided.");
            }
            return $this->getListEntity($searchCriteria, $searchResult, $ruleCollection);
        } catch (Exception $e) {
            $this->logger->error("issue when fetch the return request details" . $e->getMessage());
            throw new LocalizedException(__('"issue when fetch the return request order ID must be provided'));
        }
    }

    /**
     * Get the entity list
     *
     * @param $searchCriteria
     * @param $searchResult
     * @param $collection
     * @return mixed
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getListEntity($searchCriteria, $searchResult, $collection)
    {
        try {
            $this->abstractManagement->checkEnabled();

            if ($searchCriteria === null) {
                $searchCriteria = $this->searchCriteriaBuilder->create();
            } else {
                $this->collectionProcessor->process($searchCriteria, $collection);
            }

            if ($collection instanceof RuleCollection) {
                foreach ($collection->getItems() as $rule) {
                    $rule->setReason($this->abstractManagement->getDataInformation($rule->getReason(), 'rma/reason'));
                    $rule->setSolution($this->abstractManagement->getDataInformation($rule->getSolution(), 'rma/solution'));
                    $rule->setAdditionalField(
                        $this->abstractManagement->getDataInformation($rule->getAdditionalField(), 'rma/additional_field')
                    );
                }
            }

            if ($collection instanceof ShippingLabelCollection) {
                foreach ($collection->getItems() as $item) {
                    if ($item->getImage()) {
                        $item->setImage($this->helperData->getFileUrl(
                            $item->getImage(),
                            Image::TEMPLATE_MEDIA_TYPE_SHIPPING_LABEL
                        ));
                    }
                }
            }

            if ($collection instanceof RequestCollection) {
                foreach ($collection->getItems() as $item) {
                    $statusId = $item->getData('status_id');
                    $statusLabel = $this->abstractManagement->getStatusLabelById($statusId);
                    $item->setRmaStatus($statusLabel);
                    $ids = $this->getRMACancelRequestStatus();
                    $idArray = explode(',', $ids);
                    $isEligible = false;
                    if (in_array($statusId, $idArray)) {
                        $isEligible = true;
                    }
                    $item->setIsReturnRequestEligible($isEligible);
                    if ($item->getFiles()) {
                        $item->setFiles($this->abstractManagement->processFileUrl($item->getFiles()));
                    }

                    if ($item->getRequestReply()) {
                        foreach ($item->getRequestReply() as $reply) {
                            if ($reply->getFiles()) {
                                $reply->setFiles($this->abstractManagement->processFileUrl($reply->getFiles()));
                            }
                        }
                    }
                    $requestItems = $item->getRequestItem();
                    foreach ($requestItems as $data) {
                        $image = $this->abstractManagement->getImageBySku($data->getSku());
                        $data->setData('image', $image);
                        $priceIncTax = $this->abstractManagement->getPriceIncTaxByUsingOrderId($data->getOrderItemId());
                        $data->setData('price_incl_tax', $priceIncTax);
                        $data->setData('price', $priceIncTax);
                        $data->setData('price_returned', $priceIncTax);
                    }
                }
            }
            $searchResult->setItems($collection->getItems());
            $searchResult->setTotalCount($collection->getSize());
            $searchResult->setSearchCriteria($searchCriteria);
            return $searchResult;
        } catch (Exception $e) {
            $this->logger->error("issue when fetch entity list" . $e->getMessage());
        }
    }

    /**
     * Get the rma customer cancel request status
     *
     * @return mixed
     */
    private function getRMACancelRequestStatus()
    {
        $rma_customer_cancel_request_status = "mprma/request/cancel_status";
        return $this->scopeConfig->getValue(
            $rma_customer_cancel_request_status,
            ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * Save the request
     *
     * @param Request $request
     *
     * @throws AlreadyExistsException
     */
    public function saveLastRequest($request)
    {
        $rmaRequest = $this->requestFactory->create();
        $this->requestResource->load($rmaRequest, $request->getRequestId());
        $respondedBy = $request->getAuthorName() . ' (' . $rmaRequest->getCustomerEmail() . ')';
        $rmaRequest->setLastRespondedBy($respondedBy);
        $this->requestResource->save($rmaRequest);
    }
}
