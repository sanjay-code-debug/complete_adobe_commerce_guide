<?php

namespace Codilar\CustomApi\Api\Data;

interface BaseUrlModelInterface
{
    /**
     * Set Base url
     *
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl(string $baseUrl);

    /**
     * Get base url
     *
     * @return string
     */
    public function getBaseUrl();

    /**
     * Set store
     *
     * @param int $store
     * @return $this
     */
    public function setStoreId(int $store);

    /**
     * Get Store
     *
     * @return int
     */
    public function getStoreId();
}
