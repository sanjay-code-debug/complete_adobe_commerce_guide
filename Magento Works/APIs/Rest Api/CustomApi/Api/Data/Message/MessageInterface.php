<?php
/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */
namespace Codilar\CustomApi\Api\Data\Message;

interface MessageInterface
{
    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return bool
     */
    public function getFirstOrder();

    /**
     * @param bool $first_order
     * @return $this
     */
    public function setFirstOrder($first_order);
}
