<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Plugin;

use Codilar\CustomApi\Model\Helper\GetProductData;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;

class OrderRepositoryPlugin
{
    public const  SYSTEM_CONFIG_VALUE = 'mprma/request/order_time_return';
    /**
     * @var GetProductData
     */
    protected GetProductData $productData;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param GetProductData $productData
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ResourceConnection $resourceConnection,
        GetProductData $productData
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resourceConnection;
        $this->productData = $productData;
    }

    /**
     * Added after plugin to add two extension value - image and description
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $result
     * @return OrderInterface
     * @throws NoSuchEntityException
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $result)
    {
        $order = $result;
        foreach ($order->getAllVisibleItems() as $item) {
            $image = $this->productData->getPdpDescription($item->getSku(), GetProductData::IMAGE);
            $description = $this->productData->getPdpDescription(
                $item->getSku(),
                GetProductData::DESCRIPTION
            );
            $extensionAttribute = $item->getExtensionAttributes();
            $extensionAttribute->setImage($image);
            $extensionAttribute->setShortDescription($description);
            $item->setExtensionAttributes($extensionAttribute);
        }
        return $result;
    }


    /**
     * After getList
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $result
     * @return OrderSearchResultInterface
     * @throws NoSuchEntityException
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $result)
    {
        foreach ($result->getItems() as $order) {
            $extensionAttribute = $order->getExtensionAttributes();
            $statusLabel = $this->getOrderStatusLabel($order->getData('status'));
            $extensionAttribute->setStatusLabel($statusLabel);
            $order->setExtensionAttributes($extensionAttribute);
            foreach ($order->getItems() as $item) {
                $image = $this->productData->getPdpDescription($item->getSku(), GetProductData::IMAGE);
                $description = $this->productData->getPdpDescription(
                    $item->getSku(),
                    GetProductData::DESCRIPTION
                );
                $formattedDate = null;
                $orderConfirmDate = $order->getUpdatedAt();
                $path = self::SYSTEM_CONFIG_VALUE;
                $value = $this->scopeConfig->getValue($path);
                if (!empty($value)) {
                    $returnPossibleDate = $this->calculateReturnPossibleDate($orderConfirmDate, $value);
                    $timestamp = strtotime($returnPossibleDate);
                    if ($orderConfirmDate > $returnPossibleDate) {
                        $formattedDate = (__("Return Not Possible"));
                    } else {
                        $formattedDate = (__("Eligible for return until ")) . date("M j, Y", $timestamp);
                    }
                }
                $extensionAttribute = $item->getExtensionAttributes();
                $extensionAttribute->setImage($image);
                $extensionAttribute->setShortDescription(ucwords(strtolower($description)));
                $extensionAttribute->setReturnPossibleDate($formattedDate);
                $item->setExtensionAttributes($extensionAttribute);
            }
        }
        return $result;
    }

    /**
     * Calculate the return possible date by adding days to the purchase date
     *
     * @param string $purchaseDate
     * @param int $daysToAdd
     * @return string
     */
    private function calculateReturnPossibleDate($purchaseDate, $daysToAdd)
    {
        $purchaseDate = new \DateTime($purchaseDate);
        $purchaseDate->modify("+$daysToAdd days");
        return $purchaseDate->format('Y-m-d H:i:s');
    }

    /**
     * @param $status
     * @return string
     */
    public function getOrderStatusLabel($status)
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $connection->getTableName('sales_order_status');
        $select = $connection->select()
            ->from($tableName, ['label'])
            ->where('status = ?', $status);
        $label = $connection->fetchOne($select);
        return $label;
    }
}
