<?php

namespace Codilar\CustomApi\Model\Api;

use Codilar\CustomApi\Api\Data\WishListItemInterface;
use Magento\Framework\DataObject;

/**
 * Wishlist item model
 */
class WishlistItem extends DataObject implements WishListItemInterface
{
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritdoc
     */
    public function setId($id):void
    {
        $this->setData(self::ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * @inheritdoc
     */
    public function setSku($sku):void
    {
        $this->setData(self::SKU, $sku);
    }

    /**
     * @inheritdoc
     */
    public function getQuantity()
    {
        return $this->getData(self::QUANTITY);
    }

    /**
     * @inheritdoc
     */
    public function setQuantity($qty):void
    {
        $this->setData(self::QUANTITY, $qty);
    }
    /**
     * @inheritdoc
     */
    public function getParentSku()
    {
        return $this->getData(self::PARENT_SKU);
    }
    /**
     * @inheritdoc
     */
    public function setParentSku($parentSku):void
    {
        $this->setData(self::PARENT_SKU, $parentSku);
    }
    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->getData(self::DESC);
    }
    /**
     * @inheritdoc
     */
    public function setDescription($description)
    {
        $this->setData(self::DESC, $description);
    }
}
