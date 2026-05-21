<?php
/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */
namespace Codilar\CustomApi\Api;

use Magento\Framework\Exception\LocalizedException;
use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartItemInterface;
use Codilar\CustomApi\Api\Data\WishListItemInterface;


interface WishListInterface
{
    /**
     * Get country list
     *
     * @param int $customerId
     * @return array
     */
    public function getWishlist(int $customerId): array;

    /**
     * Get Wishlist item details for guest
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return array
     */
    public function getWishlistItemsData(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
    /**
     * Save Wishlist Product.
     *
     * @param int $customerId comment
     * @param int $productId  comment
     * @return boolean
     * @throws LocalizedException|NoSuchEntityException|Exception
     */
    public function save($customerId, $productId);

    /**
     * Delete Wishlist product by customer id
     *
     * @param int $customerId comment
     * @param int $productId comment
     * @return boolean
     * @throws LocalizedException|Exception
     */
    public function delete($customerId, $productId);

    /**
     * Clear all wishlist products
     *
     * @param int $customerId comment
     * @return boolean
     * @throws LocalizedException|NoSuchEntityException|Exception
     */
    public function clearALL($customerId);

    /**
     * Delete Wishlist product by customer id
     *
     * @param int $customerId comment
     * @param int $productId comment
     * @param int $qty comment
     * @return boolean
     * @throws LocalizedException|Exception
     */
    public function moveToCart($customerId, $productId, $qty);

    /**
     * Add all wishlist product to cart
     *
     * @param int $customerId
     * @return boolean
     */
    public function allToCart($customerId);

    /**
     * Add Wishlist items to guest cart
     *
     * @param CartItemInterface[] $cartItems
     * @throws LocalizedException
     * @return boolean
     */
    public function addItemsToCartGuest(array $cartItems);

    /**
     * Share all wishlist products to user
     *
     * @param int $customerId
     * @param string $recipientEmails
     * @param string $message
     * @return mixed
     */
    public function shareWishListDetails(int $customerId, string $recipientEmails, string $message);

    /**
     * Share all wishlist products to user
     *
     * @param int $customerId
     * @param WishListItemInterface[] $wishListItems
     * @return boolean
     */
    public function addProductsToWishList(int $customerId, array $wishListItems);
}
