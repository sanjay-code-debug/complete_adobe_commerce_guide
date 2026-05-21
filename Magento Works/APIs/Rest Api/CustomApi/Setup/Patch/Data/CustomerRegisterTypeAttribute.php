<?php

namespace Codilar\CustomApi\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Attribute;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface as PsrLogger;

class CustomerRegisterTypeAttribute implements DataPatchInterface
{
    public const CUSTOMER_REGISTER_TYPE_ATTRIBUTE = "customer_register_type";
    private SetFactory $attributeSetFactory;
    private Attribute $attributeResource;
    private PsrLogger $logger;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        SetFactory $attributeSetFactory,
        Attribute $attributeResource,
        PsrLogger $logger
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeResource = $attributeResource;
        $this->logger = $logger;
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
        try {
            $this->moduleDataSetup->getConnection()->startSetup();
            $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();
            /** @var $attributeSet Set */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
            $customerSetup->addAttribute(
                Customer::ENTITY,
                self::CUSTOMER_REGISTER_TYPE_ATTRIBUTE,
                [
                    'label' => 'Customer Register Type',
                    'input' => 'text',
                    'type' => 'varchar',
                    'required' => false,
                    'visible' => false,
                    'system' => false,
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'is_searchable_in_grid' => false,
                    'is_user_defined' => true
                ]
            );

            $attribute = $customerSetup->getEavConfig()->getAttribute(
                Customer::ENTITY,
                self::CUSTOMER_REGISTER_TYPE_ATTRIBUTE
            );

            $attribute->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId

            ]);

            $this->attributeResource->save($attribute);
            $this->moduleDataSetup->getConnection()->endSetup();
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }
}
