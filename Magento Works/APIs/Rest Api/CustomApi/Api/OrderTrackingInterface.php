<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Api;

interface OrderTrackingInterface
{
    /**
     * Order tracking details
     *
     * @param string $increment_id
     * @param string $billing_lastname
     * @param string $email
     * @return mixed
     */
    public function orderTrackingDetails(string $increment_id, string $billing_lastname, string $email);
}

