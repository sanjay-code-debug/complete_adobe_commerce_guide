<?php

namespace Codilar\CustomApi\Api;

interface InitializeCartManagementInterface
{
    /**
     * Initialize guest cart
     *
     * @param string $cartMask
     * @return boolean
     */
    public function initilizeGuestCart($cartMask);

    /**
     * Initialize login cart
     *
     * @param int $customerId
     * @return boolean
     */
    public function initilizeLoginCart($customerId);
}
