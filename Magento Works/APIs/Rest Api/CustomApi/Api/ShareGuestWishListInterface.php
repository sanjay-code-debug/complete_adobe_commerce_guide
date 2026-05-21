<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\ProductDetailInterface;

interface ShareGuestWishListInterface
{

    /**
     * Share all wishlist products to user
     *
     * @param ProductDetailInterface[] $productDetails
     * @param string $senderName
     * @param string $recipientEmails
     * @param string $message
     * @return bool
     */
    public function shareGuestWishList(
        array $productDetails,
        string $senderName,
        string $recipientEmails,
        string $message
    );
}
