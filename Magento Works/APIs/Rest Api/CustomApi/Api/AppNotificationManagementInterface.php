<?php

namespace Codilar\CustomApi\Api;

interface AppNotificationManagementInterface
{
    /**
     * Update klaviyo customer profile
     *
     * @param string $externalId
     * @param string $customerEmail
     * @return boolean
     */
    public function updateCutomerKlaviyo($externalId, $customerEmail);
}
