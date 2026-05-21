<?php

namespace Codilar\CustomApi\Api\Data;

interface AppUpdateInterface
{
    public const IS_FORCE_UPDATE = "is_force_update";
    public const TITLE = "title";
    public const DESCRIPTION = "description";

    /**
     * @param bool $isForceUpdate
     * @return $this
     */
    public function setISForceUpdate($isForceUpdate);

    /**
     * @return bool
     */
    public function getIsForceUpdate();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getDescription();

}
