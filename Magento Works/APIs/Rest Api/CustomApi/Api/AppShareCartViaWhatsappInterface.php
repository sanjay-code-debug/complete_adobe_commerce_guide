<?php

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\Message\ShareShoppingCartMessageInterface;

interface AppShareCartViaWhatsappInterface
{
    /**
     * share cart via whatsApp for guest
     *
     * @param string $quoteId
     * @return ShareShoppingCartMessageInterface
     *
     */
    public function shareCartViaWhatsapp(string $quoteId);
}
