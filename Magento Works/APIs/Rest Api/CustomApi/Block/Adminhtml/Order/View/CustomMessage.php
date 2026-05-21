<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Block\Adminhtml\Order\View;

use Magento\Framework\View\Element\Template;

class CustomMessage extends Template
{
    /**
     * @var
     */
    protected $_order;

    /**
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        if (!$this->_order) {
            $this->_order = $this->getParentBlock()->getOrder();
        }
        return $this->_order;
    }

    /**
     * @return mixed
     */
    public function getAppVersion()
    {
        return $this->getOrder()->getData('app_version');
    }

    /**
     * @return mixed
     */
    public function getIphoneModelType()
    {
        return $this->getOrder()->getData('iphone_model_type');
    }
}
