<?php

namespace Codilar\CustomApi\Api\Data\DeepLinkResponse;

interface DeepLinkFilterResponseInterface
{
    /**
     * @return string
     */
    public function getKey();
    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key);
    /**
     * @return array
     */
    public function getValues();

    /**
     * @param array $values
     * @return $this
     */
    public function setValues(array $values);
}
