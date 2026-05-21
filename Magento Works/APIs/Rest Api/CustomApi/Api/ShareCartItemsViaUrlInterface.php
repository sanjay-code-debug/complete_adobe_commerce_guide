<?php

namespace Codilar\CustomApi\Api;

interface ShareCartItemsViaUrlInterface
{
    /**
     * Get the share cart items  when share by url
     *
     * @param string $uniqueId
     * @param int $currentQuoteId
     * @return mixed
     */
    public function getShareCartItems(
        string $uniqueId,
        int $currentQuoteId
    );
}
