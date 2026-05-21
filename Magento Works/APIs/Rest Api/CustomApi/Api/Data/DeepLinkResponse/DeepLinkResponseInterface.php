<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Api\Data\DeepLinkResponse;

interface DeepLinkResponseInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return \Codilar\CustomApi\Api\Data\DeepLinkResponse\DeepLinkFilterResponseInterface[]
     */
    public function getFilters(): array;

    /**
     * @param \Codilar\CustomApi\Api\Data\DeepLinkResponse\DeepLinkFilterResponseInterface[] $filters
     * @return DeepLinkResponseInterface
     */
    public function setFilters(
        array $filters
    ): DeepLinkResponseInterface;

    /**
     * @return string
     */
    public function getSortType();

    /**
     * @param string $sort_type
     * @return $this
     */
    public function setSortType(string $sort_type);
}
