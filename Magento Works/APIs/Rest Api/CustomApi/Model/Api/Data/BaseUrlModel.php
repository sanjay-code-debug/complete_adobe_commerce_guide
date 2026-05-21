<?php

namespace Codilar\CustomApi\Model\Api\Data;

use Magento\Framework\DataObject;
use Codilar\CustomApi\Api\Data\BaseUrlModelInterface;

class BaseUrlModel extends DataObject implements BaseUrlModelInterface
{
    /**
     * @inheritdoc
     */
    public function setBaseUrl(string $baseUrl)
    {
        return $this->setData("base_url", $baseUrl);
    }
    /**
     * @inheritdoc
     */
    public function getBaseUrl()
    {
        return $this->getData("base_url");
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
}
