<?php
/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */
namespace Codilar\CustomApi\Api;

use Exception;
use Magento\Framework\Exception\LocalizedException;

interface CustomCartInterface
{
    /**
     * Move cart items to wishlist
     *
     * @param int $customerId comment
     * @param string $sku comment
     * @param int $qty comment
     * @return boolean
     * @throws LocalizedException|Exception
     */
    public function moveToWishlist($customerId, $sku, $qty);

}
