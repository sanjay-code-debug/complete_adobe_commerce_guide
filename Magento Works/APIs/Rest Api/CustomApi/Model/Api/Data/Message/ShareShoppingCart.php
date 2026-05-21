<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model\Api\Data\Message;

use Codilar\CustomApi\Api\Data\Message\ShareShoppingCartMessageInterface;
use Magento\Framework\DataObject;

class ShareShoppingCart extends DataObject implements ShareShoppingCartMessageInterface
{
    /**
     * @return bool
     */
    public function getError()
    {
        return $this->getData('error');
    }

    /**
     * @param bool $error
     * @return ShareShoppingCart
     */
    public function setError($error)
    {
        return $this->setData('error', $error);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getData('message');
    }

    /**
     * @param string $message
     * @return ShareShoppingCart
     */
    public function setMessage($message)
    {
        return $this->setData('message', $message);
    }

    /**
     * @return string
     */
    public function getQuoteId()
    {
        return $this->getData('quoteId');
    }

    /**
     * @param string $quoteId
     * @return ShareShoppingCart
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData('quoteId', $quoteId);
    }

    /**
     * @return string
     */
    public function getSharedCart()
    {
        return $this->getData('shared_cart');
    }

    /**
     * @param string $shared_cart
     * @return ShareShoppingCart
     */
    public function setSharedCart($shared_cart)
    {
        return $this->setData('shared_cart', $shared_cart);
    }
}
