<?php

namespace Codilar\CustomApi\Api;

use Codilar\CustomApi\Api\Data\AppUpdateInterface;

/**
 * App update management interface
 */
interface AppUpdateManagementInterface
{
    const FORCED_UPDATE_VERSION_CONFIG = "footer_config/force_app_update/force_update_version";
    const UPDATE_TITLE_VERSION_ = "footer_config/force_app_update/app_update_title";
    const UPDATE_DESCRIPTION_VERSION_CONFIG = "footer_config/force_app_update/app_update_description";

    /**
     * @param string $customerAppVersion
     * @return AppUpdateInterface
     */
    public function getAppUpdateDetails($customerAppVersion);
}
