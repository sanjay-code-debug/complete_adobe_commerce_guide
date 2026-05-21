<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model\Api\Data\DeepLinkResponseFormat;

use Codilar\CustomApi\Api\Data\DeepLinkResponse\DeepLinkResponseInterface;
use Magento\Framework\DataObject;

class DeepLinkResponse extends DataObject implements DeepLinkResponseInterface
{

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * @param string $type
     * @return DeepLinkResponse
     */
    public function setType($type)
    {
        return $this->setData('type', $type);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->getData('value');
    }

    /**
     * @param string $value
     * @return DeepLinkResponse
     */
    public function setValue($value)
    {
        return $this->setData('value', $value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @param string $name
     * @return DeepLinkResponse
     */
    public function setName($name)
    {
        return $this->setData('name', $name);
    }

    /**
     * @inerhitDoc
     */
    public function getFilters(): array
    {
        return $this->getData('filters');
    }

    /**
     * @inerhitDoc
     */
    public function setFilters(array $filters): DeepLinkResponseInterface
    {
        return $this->setData('filters', $filters);
    }
    /**
     * @return string
     */
    public function getSortType()
    {
        return $this->getData('sort_type');
    }
    /**
     * @param string $sort_type
     * @return $this
     */
    public function setSortType(string $sort_type)
    {
        return $this->setData('sort_type', $sort_type);
    }
}
