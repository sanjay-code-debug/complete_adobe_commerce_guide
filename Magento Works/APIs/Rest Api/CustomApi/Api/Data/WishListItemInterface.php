<?php

namespace Codilar\CustomApi\Api\Data;

/**
 * Wishlist item
 */
interface WishListItemInterface
{
    public const ID ="id";
    public const SKU ="sku";
    public const QUANTITY ="quantity";
    public const PARENT_SKU ="parentSku";

    public const DESC = "description";

    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Set Id
     *
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * Get Sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Set sku
     *
     * @param string $sku
     * @return void
     */
    public function setSku($sku);

    /**
     * Get Quantity
     *
     * @return float
     */
    public function getQuantity();

    /**
     * Set Quantity
     *
     * @param float $qty
     * @return void
     */
    public function setQuantity($qty);

    /**
     * Get Parent Sku
     *
     * @return string
     */
    public function getParentSku();

    /**
     * Set Parent Sku
     *
     * @param string $parentSku
     * @return void
     */
    public function setParentSku($parentSku);
    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description);
}
