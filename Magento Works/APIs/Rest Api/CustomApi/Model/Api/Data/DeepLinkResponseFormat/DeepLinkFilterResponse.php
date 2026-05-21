<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model\Api\Data\DeepLinkResponseFormat;

use Codilar\CustomApi\Api\Data\DeepLinkResponse\DeepLinkFilterResponseInterface;
use Magento\Framework\DataObject;

class DeepLinkFilterResponse extends DataObject implements DeepLinkFilterResponseInterface
{
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->getData('key');
    }
    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        return $this->setData('key', $key);
    }
    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getData('values');
    }

    /**
     * @param array $values
     * @return DeepLinkFilterResponse
     */
    public function setValues(array $values)
    {
        return $this->setData('values', $values);
    }
}
