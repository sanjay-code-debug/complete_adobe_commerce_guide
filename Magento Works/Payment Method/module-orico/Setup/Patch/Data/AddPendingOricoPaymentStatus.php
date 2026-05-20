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

namespace CasioJP\Orico\Setup\Patch\Data;

use Exception;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Status;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;

/**
 * Class Adding orico pending status
 */
class AddPendingOricoPaymentStatus implements DataPatchInterface, PatchRevertableInterface
{
    private const STATUS_CASIOJP_PENDING_ORICO_CODE = 'pending_orico_payment';
    private const STATUS_CASIOJP_PENDING_ORICO_LABEL = 'Pending Orico';

    /**
     * @var StatusFactory
     */
    protected StatusFactory $statusFactory;

    /**
     * @var StatusResourceFactory
     */
    protected StatusResourceFactory $statusResourceFactory;

    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * AddPendingOricoPaymentStatus constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param StatusFactory $statusFactory
     * @param StatusResourceFactory $statusResourceFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        StatusFactory            $statusFactory,
        StatusResourceFactory    $statusResourceFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->statusFactory = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var \Magento\Sales\Model\ResourceModel\Order\Status $statusResource */
        $statusResource = $this->statusResourceFactory->create();

        /** @var Status $status */
        $status = $this->statusFactory->create();
        $status->setData([
            'status' => self::STATUS_CASIOJP_PENDING_ORICO_CODE,
            'label' => self::STATUS_CASIOJP_PENDING_ORICO_LABEL,
        ]);
        try {
            $statusResource->save($status);
            $status->assignState(Order::STATE_PROCESSING, false, true);
        } catch (Exception $e) {
            return;
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $salesOrderStatusTable = $this->moduleDataSetup->getTable('sales_order_status');
        $salesOrderStatusStateTable = $this->moduleDataSetup->getTable('sales_order_status_state');

        $this->moduleDataSetup->getConnection()
            ->delete($salesOrderStatusStateTable, ['status = ?' => self::STATUS_CASIOJP_PENDING_ORICO_CODE]);
        $this->moduleDataSetup->getConnection()
            ->delete($salesOrderStatusTable, ['status = ?' => self::STATUS_CASIOJP_PENDING_ORICO_CODE]);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
