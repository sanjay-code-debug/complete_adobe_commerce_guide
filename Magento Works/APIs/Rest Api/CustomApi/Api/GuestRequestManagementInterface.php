<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2024 Codilar (https://www.codilar.com/)
 */


namespace Codilar\CustomApi\Api;

use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Mageplaza\RMA\Api\Data\RequestReplyInterface;
use Mageplaza\RMA\Api\SearchResult\RequestSearchResultInterface;

interface GuestRequestManagementInterface
{
    /**
     * Get the return request
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     *
     * @return RequestSearchResultInterface
     */
    public function getGuestRequest(SearchCriteriaInterface $searchCriteria = null);

    /**
     * Reply the guest return request
     *
     * @param RequestReplyInterface $reply
     *
     * @return RequestReplyInterface
     * @throws Exception
     */
    public function saveGuestReply(RequestReplyInterface $reply);

    /**
     * Cancel the return request
     *
     * @param RequestReplyInterface $cancel
     *
     * @return RequestReplyInterface
     * @throws Exception
     */
    public function cancel(RequestReplyInterface $cancel);
}
