<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\Message\PopularSearchResultInterface;

interface PopularSearchInterface
{
    /**
     * Get popular searches
     * @return PopularSearchResultInterface.
     */
    public function getPopularSearches(): PopularSearchResultInterface;
}
