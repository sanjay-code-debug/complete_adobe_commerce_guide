<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model\Data;

use Codilar\CustomApi\Api\Data\ProductDetailInterface;
use Magento\Framework\DataObject;

class ProductDetail extends DataObject implements ProductDetailInterface
{
    /**
     * @inheritdoc
     */
    public function getProductIds()
    {
        return $this->getData('productIds');
    }

    /**
     * @inheritdoc
     */
    public function setProductIds($productIds)
    {
        return $this->setData('productIds', $productIds);
    }
}
