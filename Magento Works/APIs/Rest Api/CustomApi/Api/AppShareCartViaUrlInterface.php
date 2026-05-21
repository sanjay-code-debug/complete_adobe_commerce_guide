<?php

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\Message\ShareShoppingCartMessageInterface;

interface AppShareCartViaUrlInterface
{
    /**
     * share cart via whatsApp for guest
     *
     * @param string $quoteId
     *
     *
     * @return ShareShoppingCartMessageInterface
     *
     */
    public function shareCartViaUrl(string $quoteId);
}
