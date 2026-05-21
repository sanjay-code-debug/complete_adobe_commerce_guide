<?php
namespace Codilar\CustomApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface AvailableFilterInterface extends ExtensibleDataInterface
{
    /**
     * @return string
     */
    public function getAttributeCode();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getOptions();

    /**
     * @return string
     */
    public function getAttributeType();
}
