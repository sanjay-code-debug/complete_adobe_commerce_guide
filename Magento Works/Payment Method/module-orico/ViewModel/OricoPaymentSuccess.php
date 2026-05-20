<?php
/**
 * ADOBE CONFIDENTIAL
 * ___________________
 *
 * Copyright 2022 Adobe
 * All Rights Reserved.
 *
 * NOTICE: All information contained herein is, and remains
 * the property of Adobe and its suppliers, if any. The intellectual
 * and technical concepts contained herein are proprietary to Adobe
 * and its suppliers and are protected by all applicable intellectual
 * property laws, including trade secret and copyright laws.
 * Adobe permits you to use and modify this file
 * in accordance with the terms of the Adobe license agreement
 * accompanying it (see LICENSE_ADOBE_PS.txt).
 * If you have received this file from a source other than Adobe,
 * then your use, modification, or distribution of it
 * requires the prior written permission from Adobe.
 */
declare(strict_types=1);

namespace CasioJP\Orico\ViewModel;

use http\Env\Request;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Magento\Store\Model\ScopeInterface;

/**
 * Orico order Success
 */
class OricoPaymentSuccess implements ArgumentInterface
{
    private const ORICO_WARNING_MESSAGE = 'casiojp_orico/orico_thank_you_page/orico_warning_message';
    private const ORICO_BUTTON_LABEL = 'casiojp_orico/orico_thank_you_page/orico_label_of_button';
    private const ORICO_TITLE_FOR_APPROVAL = 'casiojp_orico/orico_thank_you_page/orico_title_for_approval_case';
    private const ORICO_GUIDANCE_FOR_APPROVAL = 'casiojp_orico/orico_thank_you_page/orico_guidance_for_approval';
    private const ORICO_REJECTED_TITLE = 'casiojp_orico/orico_thank_you_page/orico_title_for_rejected_case';
    private const ORICO_GUIDANCE_FOR_REJECT = 'casiojp_orico/orico_thank_you_page/orico_guidance_for_rejected_case';
    private const ORICO_CONTRACT_NUMBER = 'casiojp_orico/orico_api/orico_contract_number';
    private const ORICO_CLIENT_NUMBER = 'casiojp_orico/orico_api/orico_client_number';
    private const ORICO_REDIRECT_URL = 'casiojp_orico/orico_api/orico_redirect_url';
    private const ORICO_API_ENDPOINT = 'casiojp_orico/orico_api/orico_api_endpoint';
    private const ORICO_PAYMENT_CODE = 'orico';
    private const ORICO_ITEM_DISCOUNT_LABEL = '値引き';
    private const ORICO_ITEM_OTHER_LABEL = 'その他';
    private const ORICO_ITEM_DISCOUNT_CODE = '0999999';

    /**
     * @var array
     */
    private $additionalKeys =  [
        'casio_goc_price',
        'casio_gob_price',
        'casio_godb_price',
        'casio_myg_godb_price',
        'gw_price',
        'casio_warranty_price',
        'casio_goo_price',
        'casio_band_price',
        'casio_engraving_price'];

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $session
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Session $session
    ) {
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check is orico payment
     *
     * @return bool
     */
    public function isOricoPaymentMethod()
    {
        $order = $this->getOrder();
        $orderPaymentMethod = $order->getPayment()->getMethod();
        if ($orderPaymentMethod == self::ORICO_PAYMENT_CODE) {
            return true;
        }
        return false;
    }

    /**
     * Get Last Order
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->session->getLastRealOrder();
    }

    /**
     * Get Orico item By sorting
     *
     * @param Order $order
     * @return array
     */
    public function getOricoItemsBySortAndDiscount($order)
    {
        $totalDiscount = 0;
        $totalAmount = 0;
        $postalCode = $order->getShippingAddress() ? $order->getShippingAddress()->getPostCode() : '';
        $replacement = '-';
        if ($postalCode && strlen($postalCode) > 3) {
            /**
             * “-“ should be added to postal code. first half should be first 1 to 3 digit,
             * and second half should be first 4 to 7 digit.
             */
            $postalCode = substr_replace($postalCode, $replacement, 3, 0);
        }

        foreach ($order->getAllVisibleItems() as $item) {
            $itemId = $item->getId();
            $additionalPrice = $this->getAdditionalPrice($item, $order);
            $items[$itemId]['item_id'] = $itemId;
            $items[$itemId]['name'] = $item->getName();
            $items[$itemId]['qty'] = $item->getQtyOrdered();
            $items[$itemId]['sub_total'] = $item->getRowTotal() + $additionalPrice;
            $totalDiscount += $item->getDiscountAmount();
            $totalAmount += $item->getRowTotal() + $additionalPrice;
        }
        $keys = array_column($items, 'sub_total');
        array_multisort($keys, SORT_DESC, $items);
        $oricoItems = [
            'items' => $items ,
            'total_discount'=>  $totalDiscount,
            'total_amount'=>$totalAmount,
            'postal_code'=>$postalCode
        ];
        return $oricoItems;
    }

    /**
     * Get Additional price from simple and bundled product items
     *
     * @param Item $item
     * @param Order $order
     * @return float|int
     */
    public function getAdditionalPrice($item, $order)
    {
        $additionalPriceTotal = 0;
        $additionalPricechildTotal = 0;
        /** For Bundle Products */
        if ($item->getProductType() == 'bundle') {
            foreach ($order->getAllItems() as $childItems) {
                if ($childItems->getParentItemId() == $item->getItemId()) {
                    $additionalPricechildTotal += $this->getAdditionalPriceFromItem($childItems);
                }
            }
        }
        $additionalPriceTotal = $this->getAdditionalPriceFromItem($item);
        if ($additionalPricechildTotal) {
            $additionalPriceTotal = $additionalPricechildTotal + $additionalPriceTotal ;
        }
        return  $additionalPriceTotal;
    }

    /**
     * Get Additional Price from items
     *
     * @param Item $item
     * @return float|int
     */
    public function getAdditionalPriceFromItem($item)
    {
        $additionalPriceTotal = 0;
        foreach ($this->additionalKeys as $key) {
            /** For Bundle Products */
            $additionalPrice = $item[$key] ?? 0;
            $additionalPriceTotal += ((float)$additionalPrice * $item->getQtyOrdered()) ;
        }
        return $additionalPriceTotal;
    }

    /**
     * Get Orico items
     *
     * @param Order $order
     * @return array
     */
    public function getOricoItems($order)
    {
        $oricoItems = $this->getOricoItemsBySortAndDiscount($order);
        $oricoSortItems = $oricoItems['items'];
        $discount = $oricoItems['total_discount'];
        $level = 0;
        $level5 = 5;
        $level4Qty = 0;
        $level4Subtotal =0;
        foreach ($oricoSortItems as $item) {
            $level ++;
            if ($discount && $level == 4) {
                $items[$level]['name'] = self::ORICO_ITEM_DISCOUNT_LABEL;
                $items[$level]['qty'] = 1;
                $items[$level]['sub_total'] = -$discount;
                $items[$level]['code'] = self::ORICO_ITEM_DISCOUNT_CODE;
                $level4Qty = $item['qty'];
                $level4Subtotal = $item['sub_total'];
                continue ;
            }
            if ($level <= 4) {
                $items[$level]['name'] = $item['name'];
                $items[$level]['qty'] = $item['qty'];
                $items[$level]['sub_total'] = $item['sub_total'];
            } else {
                $items[$level5]['name'] = self::ORICO_ITEM_OTHER_LABEL;
                if (isset($items[$level5]['qty']) && isset($items[$level5]['sub_total'])) {
                    $items[$level5]['qty'] += $item['qty'];
                    $items[$level5]['sub_total'] += $item['sub_total'];
                } else {
                    $items[$level5]['qty'] = $item['qty'] + $level4Qty;
                    $items[$level5]['sub_total'] = $item['sub_total'] + $level4Subtotal;
                }
            }
        }
        if ($discount && $level <= 3) {
            $level++;
            $items[$level]['name'] = self::ORICO_ITEM_DISCOUNT_LABEL;
            $items[$level]['qty'] = 1;
            $items[$level]['sub_total'] = -$discount;
            $items[$level]['code'] = self::ORICO_ITEM_DISCOUNT_CODE;
        }
        return $items;
    }

    /**
     * Get orico warning message
     *
     * @return mixed
     */
    public function getOricoWarningMessage()
    {
        return $this->getValue(self::ORICO_WARNING_MESSAGE);
    }

    /**
     * Get orico API endpoint
     *
     * @return mixed
     */
    public function getOricoEndpoint()
    {
        return $this->getValue(self::ORICO_API_ENDPOINT);
    }

    /**
     * Get orico client number
     *
     * @return mixed
     */
    public function getClientNumber()
    {
        return $this->getValue(self::ORICO_CLIENT_NUMBER);
    }

    /**
     * Get orico contract number
     *
     * @return mixed
     */
    public function getContractNumber()
    {
        return $this->getValue(self::ORICO_CONTRACT_NUMBER);
    }

    /**
     * Get orico redirect url
     *
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->getValue(self::ORICO_REDIRECT_URL);
    }

    /**
     * An alias for scope config with default scope type SCOPE_STORE
     *
     * @param string $path
     * @param int|ScopeInterface|null $storeId Scope code
     * @param string $scope
     *
     * @return mixed
     */
    private function getValue($path, $storeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        if ($storeId instanceof \Magento\Framework\App\ScopeInterface) {
            $storeId = $storeId->getId();
        }
        $scopeKey = $storeId;
        if ($scopeKey === null) {
            $scopeKey = 'current_';
        }
        $scopeKey .= $scope;
        if (empty($this->data[$path][$scopeKey])) {
            $this->data[$path][$scopeKey] = $this->scopeConfig->getValue($path, $scope, $storeId);
        }

        return $this->data[$path][$scopeKey];
    }

    /**
     * Get orico button label
     *
     * @return mixed
     */
    public function getOricoButtonLabel()
    {
        return $this->getValue(self::ORICO_BUTTON_LABEL);
    }

    /**
     * Get orico title for approval
     *
     * @return mixed
     */
    public function getOricoTitleForApproval()
    {
        return $this->getValue(self::ORICO_TITLE_FOR_APPROVAL);
    }

    /**
     * Get orico for guidance for approval
     *
     * @return mixed
     */
    public function getOricoGuidanceForApproval()
    {
        return $this->getValue(self::ORICO_GUIDANCE_FOR_APPROVAL);
    }

    /**
     * Get orico rejected Title
     *
     * @return mixed
     */
    public function getOricoRejectedTitle()
    {
        return $this->getValue(self::ORICO_REJECTED_TITLE);
    }

    /**
     * Get orico guidance for rejected
     *
     * @return mixed
     */
    public function getOricoGuidanceForRejected()
    {
        return $this->getValue(self::ORICO_GUIDANCE_FOR_REJECT);
    }
}
