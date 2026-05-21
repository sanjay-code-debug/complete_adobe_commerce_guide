<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Api\Data\Message;

interface ShareShoppingCartMessageInterface
{

    /**
     * @return bool
     */
    public function getError();

    /**
     * @param bool $error
     * @return $this
     */
    public function setError($error);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getQuoteId();

    /**
     * @param string $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId);

    /**
     * @return string
     */
    public function getSharedCart();

    /**
     * @param string $shared_cart
     * @return $this
     */
    public function setSharedCart($shared_cart);
}
