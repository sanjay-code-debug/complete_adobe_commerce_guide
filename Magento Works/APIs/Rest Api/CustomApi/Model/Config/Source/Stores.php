<?php

namespace Codilar\CustomApi\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;

class Stores implements OptionSourceInterface
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * Stores constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $storeManagerDataList = $this->storeManager->getStores();
        $options = [];
        foreach ($storeManagerDataList as $value) {
            $labelName = $value->getWebsite()->getName() . ' : ' . $value->getName();
            $options[] = [
                'label' => $labelName,
                'value' => $value->getId()
            ];
        }
        return $options;
    }
}
