<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Api\Data;

interface ProductDetailInterface
{
    /**
     * Get product ID
     *
     * @return string
     */
    public function getProductIds();

    /**
     * Set product ID
     *
     * @param string $productIds
     * @return $this
     */
    public function setProductIds($productIds);
}
