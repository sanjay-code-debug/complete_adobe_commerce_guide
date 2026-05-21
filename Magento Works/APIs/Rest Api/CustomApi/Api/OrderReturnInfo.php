<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Api;

interface OrderReturnInfo
{
    /**
     * Order return information
     *
     * @param string $orderId
     * @return mixed
     */
    public function orderReturnInfo(string $orderId);
}
