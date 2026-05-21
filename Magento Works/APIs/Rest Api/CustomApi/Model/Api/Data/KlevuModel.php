<?php

namespace Codilar\CustomApi\Model\Api\Data;

use Codilar\CustomApi\Api\Data\KlevuModelInterface;
use Magento\Framework\DataObject;

class KlevuModel extends DataObject implements KlevuModelInterface
{
    /**
     * @inheritdoc
     */
    public function setApiKey(string $apiKey)
    {
        return $this->setData("api_key", $apiKey);
    }
    /**
     * @inheritdoc
     */
    public function getApiKey()
    {
        return $this->getData("api_key");
    }
    /**
     * @inheritdoc
     */
    public function setStoreId(int $storeId)
    {
        return $this->setData("store_id", $storeId);
    }
    /**
     * @inheritdoc
     */
    public function getStoreId()
    {
        return $this->getData("store_id");
    }
    /**
     * @inheritdoc
     */
    public function setSearchUrl(string $searchUrl)
    {
        return $this->setData("search_url", $searchUrl);
    }
    /**
     * @inheritdoc
     */
    public function getSearchUrl()
    {
        return $this->getData("search_url");
    }
    /**
     * @inheritdoc
     */
    public function setRecommendedNewArrivalUrl(string $url)
    {
        return $this->setData("recommended_new_arrival_url", $url);
    }
    /**
     * @inheritdoc
     */
    public function getRecommendedNewArrivalUrl()
    {
        return $this->getData("recommended_new_arrival_url");
    }
}
