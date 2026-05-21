<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\DeepLinkResponse\DeepLinkResponseInterface;

interface UrlDeeplinkInterface
{
    /**
     * Url deeplink validation
     *
     * @param string $url
     * @return DeepLinkResponseInterface
     * @throws \Exception
     */
    public function urlDeepLinkValidation(
        string $url
    );
}
