<?php

namespace Codilar\CustomApi\Model\Api\Data\Message;

use Codilar\CustomApi\Api\Data\Message\MessageInterface;
use Magento\Framework\DataObject;

class Message extends DataObject implements MessageInterface
{
    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getData('message');
    }

    /**
     * @param string $message
     * @return Message
     */
    public function setMessage($message)
    {
        return $this->setData('message', $message);
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->getData('status');
    }

    /**
     * @param bool $status
     * @return Message
     */
    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }

    /**
     * @return bool
     */
    public function getFirstOrder()
    {
        return $this->getData('first_order');
    }

    /**
     * @param $first_order
     * @return Message
     */
    public function setFirstOrder($first_order)
    {
        return $this->setData('first_order', $first_order);
    }
}
