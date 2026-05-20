<?php

namespace Custom\PaymentMethod\Model;

use Magento\Payment\Model\Method\AbstractMethod;

class CustomPayment extends AbstractMethod
{
    /**
     * Payment Method Code
     */
    protected $_code = 'custompaymentmethod';
}
