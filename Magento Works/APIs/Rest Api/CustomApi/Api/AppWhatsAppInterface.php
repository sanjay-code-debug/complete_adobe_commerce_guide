<?php

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\Message\ShareShoppingCartMessageInterface;

interface AppWhatsAppInterface
{
    /**
     * share cart via whatsApp.
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
    public function appWhatsApp($senderName, $senderEmail, $quoteId, $customerId = null, $isQuoteRequest = false);
}
