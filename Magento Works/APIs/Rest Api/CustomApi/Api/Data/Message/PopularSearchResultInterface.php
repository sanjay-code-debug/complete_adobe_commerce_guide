<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Api\Data\Message;

interface PopularSearchResultInterface
{
    /**
     * Set popular searches.
     *
     * @param array $popularSearches
     * @return $this
     */
    public function setPopularSearches(array $popularSearches): PopularSearchResultInterface;

    /**
     * Get popular searches.
     *
     * @return array
     */
    public function getPopularSearches(): array;
}
