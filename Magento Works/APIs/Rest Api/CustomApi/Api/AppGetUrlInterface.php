<?php

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\Message\ShareShoppingCartMessageInterface;

interface AppGetUrlInterface
{
    /**
     * share cart via url.
     *
     * @param string $senderName
     *
     * @param string $senderEmail
     *
     * @param int $quoteId
     *
     * @param int|null $customerId
     *
     * @param bool $isQuoteRequest
     *
     * @return ShareShoppingCartMessageInterface
     * @api
     *
     */
    public function appGetUrl($senderName, $senderEmail, $quoteId, $customerId = null, $isQuoteRequest = false);
}
