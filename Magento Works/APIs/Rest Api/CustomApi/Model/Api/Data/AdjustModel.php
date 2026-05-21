<?php

namespace Codilar\CustomApi\Model\Api\Data;

use Codilar\CustomApi\Api\Data\AdjustModelInterface;
use Magento\Framework\DataObject;

class AdjustModel extends DataObject implements AdjustModelInterface
{
    /**
     * @inheritdoc
     */
    public function getAdjustToken()
    {
        return $this->getData("adjust_token");
    }
    /**
     * @inheritdoc
     */
    public function setAdjustToken(string $adjustToken)
    {
        return $this->setData("adjust_token", $adjustToken);
    }
    /**
     * @inheritdoc
     */
    public function setSearchEvent(string $searchEvent)
    {
        return $this->setData("search_event", $searchEvent);
    }
    /**
     * @inheritdoc
     */
    public function getSearchEvent()
    {
        return $this->getData("search_event");
    }
    /**
     * @inheritdoc
     */
    public function setShippingEvent(string $shippingEvent)
    {
        return $this->setData("shipping_event", $shippingEvent);
    }
    /**
     * @inheritdoc
     */
    public function getShippingEvent()
    {
        return $this->getData("shipping_event");
    }
    /**
     * @inheritdoc
     */
    public function setPaymentEvent(string $paymentEvent)
    {
        return $this->setData("payment_event", $paymentEvent);
    }
    /**
     * @inheritdoc
     */
    public function getPaymentEvent()
    {
        return $this->getData("payment_event");
    }
    /**
     * @inheritdoc
     */
    public function setAddToCartEvent(string $addToCartEvent)
    {
        return $this->setData("add_to_cart_event", $addToCartEvent);
    }
    /**
     * @inheritdoc
     */
    public function getAddToCartEvent()
    {
        return $this->getData("add_to_cart_event");
    }
    /**
     * @inheritdoc
     */
    public function setLoginEvent(string $loginEvent)
    {
        return $this->setData("login_event", $loginEvent);
    }
    /**
     * @inheritdoc
     */
    public function getLoginEvent()
    {
        return $this->getData("login_event");
    }
    /**
     * @inheritdoc
     */
    public function setRegistrationEvent(string $registrationEvent)
    {
        return $this->setData("registration_event", $registrationEvent);
    }
    /**
     * @inheritdoc
     */
    public function getRegistrationEvent()
    {
        return $this->getData("registration_event");
    }
    /**
     * @inheritdoc
     */
    public function setSaleEvent(string $saleEvent)
    {
        return $this->setData("sale_event", $saleEvent);
    }
    /**
     * @inheritdoc
     */
    public function getSaleEvent()
    {
        return $this->getData("sale_event");
    }
    /**
     * @inheritdoc
     */
    public function setViewCartEvent(string $viewCartEvent)
    {
        return $this->setData("view_cart_event", $viewCartEvent);
    }
    /**
     * @inheritdoc
     */
    public function getViewCartEvent()
    {
        return $this->getData("view_cart_event");
    }
    /**
     * @inheritdoc
     */
    public function setViewListingEvent(string $viewListingEvent)
    {
        return $this->setData("view_listing_event", $viewListingEvent);
    }
    /**
     * @inheritdoc
     */
    public function getViewListingEvent()
    {
        return $this->getData("view_listing_event");
    }
    /**
     * @inheritdoc
     */
    public function setViewProductEvent(string $viewProductEvent)
    {
        return $this->setData("view_product_event", $viewProductEvent);
    }
    /**
     * @inheritdoc
     */
    public function getViewProductEvent()
    {
        return $this->getData("view_product_event");
    }
    /**
     * @inheritdoc
     */
    public function setFirstSaleEvent(string $firstSaleEvent)
    {
        return $this->setData("first_sale_event", $firstSaleEvent);
    }
    /**
     * @inheritdoc
     */
    public function getFirstSaleEvent()
    {
        return $this->getData("first_sale_event");
    }
    /**
     * @inheritdoc
     */
    public function setAddToWishlistEvent(string $addToWishlistEvent)
    {
        return $this->setData("add_to_wish_list_event", $addToWishlistEvent);
    }
    /**
     * @inheritdoc
     */
    public function getAddToWishlistEven()
    {
        return $this->getData("add_to_wish_list_event");
    }
    /**
     * @inheritdoc
     */
    public function getStoreId()
    {
        return $this->getData("store_id");
    }
    /**
     * @inheritdoc
     */
    public function setStoreId($storeId)
    {
        return $this->setData("store_id", $storeId);
    }
}
