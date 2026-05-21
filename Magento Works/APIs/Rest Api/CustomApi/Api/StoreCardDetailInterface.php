<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Api;

interface StoreCardDetailInterface
{
    /**
     * Store the card details of user
     *
     * @param int $customerId
     * @param string $type
     * @param string $number
     * @param string $expiry_month
     * @param string $expiry_year
     * @param string $cvv
     * @return string|boolean
     */
    public function storedPaymentMethods(
        int $customerId,
        string $type,
        string $number,
        string $expiry_month,
        string $expiry_year,
        string $cvv
    );

    /**
     * Get card details
     *
     * @param int $customerId
     * @return array
     */
    public function fetchCardDetails(int $customerId): array;

    /**
     * Delete card  by customer id
     *
     * @param int $customerId comment
     * @param string $hashValue
     * @return mixed
     */
    public function deleteCard(int $customerId, string $hashValue);
}
