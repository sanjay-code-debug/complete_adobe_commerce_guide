<?php

namespace Codilar\CustomApi\Model\Api\Data;

use Codilar\CustomApi\Api\Data\AppUpdateInterface;
use Magento\Framework\DataObject;

/**
 * App update data model
 */
class AppUpdate extends DataObject implements AppUpdateInterface
{
    /**
     * {@inheritdoc}
     */
    public function setISForceUpdate($isForceUpdate)
    {
      return $this->setData(self::IS_FORCE_UPDATE, $isForceUpdate);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsForceUpdate()
    {
        return $this->getData(self::IS_FORCE_UPDATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }
}
