<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderSearchResultInterface;

interface OrderRepositoryInterface
{
    /**
     * Add Payment Transaction ID after successfull payment done
     *
     * @param string $orderId
     * @param string $authoriseTxnId
     * @param string $capturedTxnId
     * @param string $paymentId
     * @return \Codilar\CustomApi\Api\Data\Message\MessageInterface
     */
    public function addTransaction($orderId, $authoriseTxnId, $capturedTxnId, $paymentId = null);

    /**
     *  Validate current store order
     *
     * @param string $orderIncrementid
     * @return boolean
     * @throws LocalizedException
     */
    public function validateOrderStore($orderIncrementid);
}
