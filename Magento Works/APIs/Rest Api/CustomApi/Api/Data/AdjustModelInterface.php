<?php

namespace Codilar\CustomApi\Api\Data;

interface AdjustModelInterface
{
    /**
     * Get adjust token
     *
     * @return string
     */
    public function getAdjustToken();

    /**
     * Set adjust token
     *
     * @param string $adjustToken
     * @return $this
     */
    public function setAdjustToken(string $adjustToken);

    /**
     * Set search event
     *
     * @param string $searchEvent
     * @return $this
     */
    public function setSearchEvent(string $searchEvent);

    /**
     * Get search event
     *
     * @return string
     */
    public function getSearchEvent();

    /**
     * Set shipping event
     *
     * @param string $shippingEvent
     * @return $this
     */
    public function setShippingEvent(string $shippingEvent);

    /**
     * Get shipping event
     *
     * @return string
     */
    public function getShippingEvent();

    /**
     * Set payment event
     *
     * @param string $paymentEvent
     * @return $this
     */
    public function setPaymentEvent(string $paymentEvent);

    /**
     * Get payment event
     *
     * @return string
     */
    public function getPaymentEvent();

    /**
     * Set add to cart event
     *
     * @param string $addToCartEvent
     * @return $this
     */
    public function setAddToCartEvent(string $addToCartEvent);

    /**
     * Get add to cart event
     *
     * @return string
     */
    public function getAddToCartEvent();

    /**
     * Set login event
     *
     * @param string $loginEvent
     * @return $this
     */
    public function setLoginEvent(string $loginEvent);

    /**
     * Get login event
     *
     * @return string
     */
    public function getLoginEvent();

    /**
     * Set registration event
     *
     * @param string $registrationEvent
     * @return $this
     */
    public function setRegistrationEvent(string $registrationEvent);

    /**
     * Get registration event
     *
     * @return string
     */
    public function getRegistrationEvent();

    /**
     * Get sales event
     *
     * @param string $saleEvent
     * @return $this
     */
    public function setSaleEvent(string $saleEvent);

    /**
     * Get sale event
     *
     * @return string
     */
    public function getSaleEvent();

    /**
     * Set view cart event
     *
     * @param string $viewCartEvent
     * @return $this
     */
    public function setViewCartEvent(string $viewCartEvent);

    /**
     * Get view cart event
     *
     * @return string
     */
    public function getViewCartEvent();

    /**
     * Set view listing event
     *
     * @param string $viewListingEvent
     * @return $this
     */
    public function setViewListingEvent(string $viewListingEvent);

    /**
     * Get view listing event
     *
     * @return string
     */
    public function getViewListingEvent();

    /**
     * Set view product event
     *
     * @param string $viewProductEvent
     * @return $this
     */
    public function setViewProductEvent(string $viewProductEvent);

    /**
     * Get view product event
     *
     * @return string
     */
    public function getViewProductEvent();

    /**
     * Set first sale event
     *
     * @param string $firstSaleEvent
     * @return $this
     */
    public function setFirstSaleEvent(string $firstSaleEvent);

    /**
     * Get first sale event
     *
     * @return string
     */
    public function getFirstSaleEvent();

    /**
     * Set add to wishlist event
     *
     * @param string $addToWishlistEvent
     * @return $this
     */
    public function setAddToWishlistEvent(string $addToWishlistEvent);

    /**
     * Get add to wishlist event
     *
     * @return string
     */
    public function getAddToWishlistEven();
    /**
     * Get store id
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set store id
     *
     * @param int $storeId
     * @return string
     */
    public function setStoreId($storeId);
}
