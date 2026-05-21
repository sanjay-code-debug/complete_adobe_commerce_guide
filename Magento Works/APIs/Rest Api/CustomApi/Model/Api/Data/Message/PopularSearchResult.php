<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model\Api\Data\Message;

use Codilar\CustomApi\Api\Data\Message\PopularSearchResultInterface;

class PopularSearchResult implements PopularSearchResultInterface
{
    /**
     * @var array
     */
    private array $popularSearches;

    /**
     * @inheritdoc
     */
    public function setPopularSearches(array $popularSearches): PopularSearchResultInterface
    {
        $this->popularSearches = $popularSearches;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPopularSearches(): array
    {
        return $this->popularSearches ?? [];
    }
}
