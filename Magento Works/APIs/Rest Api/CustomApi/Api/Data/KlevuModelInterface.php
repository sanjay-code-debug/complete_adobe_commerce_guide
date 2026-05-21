<?php

namespace Codilar\CustomApi\Api\Data;

interface KlevuModelInterface
{
    /**
     * Set Api Key
     *
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey(string $apiKey);

    /**
     * Get API key
     *
     * @return string
     */
    public function getApiKey();

    /**
     * Set search url
     *
     * @param string $searchUrl
     * @return $this
     */
    public function setSearchUrl(string $searchUrl);

    /**
     * Get search url
     *
     * @return string
     */
    public function getSearchUrl();

    /**
     * Set storeId
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId);

    /**
     * Get store Id
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set recommended new arrival url
     *
     * @param string $url
     * @return $this
     */
    public function setRecommendedNewArrivalUrl(string $url);
    /**
     * Get recommended new arrival url
     *
     * @return string
     */
    public function getRecommendedNewArrivalUrl();

}
