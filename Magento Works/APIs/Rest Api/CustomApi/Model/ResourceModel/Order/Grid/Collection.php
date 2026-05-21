<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model\ResourceModel\Order\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Sales\Model\ResourceModel\Order;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OrderGridCollection;
use Psr\Log\LoggerInterface as Logger;

class Collection extends OrderGridCollection
{
    /**
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param $mainTable
     * @param $resourceModel
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable,
        $resourceModel = Order::class
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable = 'sales_order_grid',
            $resourceModel
        );
    }

    protected function _renderFiltersBefore()
    {
        $joinTable = $this->getTable('sales_order');
        $this->getSelect()->joinLeft(
            ['sales_order' => $joinTable],
            'main_table.entity_id = sales_order.entity_id',
            ['app_version','iphone_model_type']
        );
        parent::_renderFiltersBefore();
    }
}
