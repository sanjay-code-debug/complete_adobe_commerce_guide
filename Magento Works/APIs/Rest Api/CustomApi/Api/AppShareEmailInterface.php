<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\Message\ShareShoppingCartMessageInterface;

interface AppShareEmailInterface
{
    /**
     * share cart via email.
     *
     * @param string $senderName
     *
     * @param string $senderEmail
     *
     * @param string $recipientEmail
     *
     * @param string|null $message
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
    public function sendAppEmail(
        $senderName,
        $senderEmail,
        $recipientEmail,
        $message,
        $quoteId,
        $customerId = null,
        $isQuoteRequest = false
    );
}
