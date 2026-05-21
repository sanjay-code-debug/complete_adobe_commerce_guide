<?php

namespace Codilar\CustomApi\Api;

interface InstantDiscountManagementInterface
{
    /**
     * Instant Discount from login in
     *
     * @param int $cartId
     * @return boolean
     */
    public function removeInstantDiscountLogin($cartId);

    /**
     * Remove Instant Discount from guest Cart
     *
     * @param int $cartId
     * @return boolean
     */
    public function guestRemoveInstantDiscount($cartId);
}
