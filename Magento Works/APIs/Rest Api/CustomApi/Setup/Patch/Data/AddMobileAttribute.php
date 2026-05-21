<?php
/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */
namespace Codilar\CustomApi\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Customer\Model\ResourceModel\Attribute;

class AddMobileAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var CustomerSetup
     */
    private $customerSetupFactory;
    /**
     * @var SetFactory
     */
    private $attributeSetFactory;

    /**
     * Define mobile attribute
     */
    private const CUSTOMER_MOBILE_ATTRIBUTE = 'mobile';

    /**
     * @var Attribute
     */
    private Attribute $attributeResource;

    /**
     * Constructor
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     * @param SetFactory $attributeSetFactory
     * @param Attribute $attributeResource
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        SetFactory $attributeSetFactory,
        Attribute $attributeResource
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeResource = $attributeResource;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        try {
            $this->moduleDataSetup->getConnection()->startSetup();
            /** @var CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet Set */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(
                Customer::ENTITY,
                self::CUSTOMER_MOBILE_ATTRIBUTE,
                [
                    'label' => 'Mobile Number',
                    'input' => 'text',
                    'type' => 'varchar',
                    'source' => '',
                    'required' => false,
                    'position' => 50,
                    'visible' => true,
                    'system' => false,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => true,
                    'is_filterable_in_grid' => true,
                    'is_searchable_in_grid' => false,
                    'is_user_defined' => true,
                    'backend' => ''
                ]
            );
            $attribute = $customerSetup->getEavConfig()->getAttribute(
                Customer::ENTITY,
                self::CUSTOMER_MOBILE_ATTRIBUTE
            );
            $attribute->addData([
                'used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId

            ]);
            $this->attributeResource->save($attribute);
            $this->moduleDataSetup->getConnection()->endSetup();
        } catch (\Exception $exception) {
            throw new LocalizedException(__('Something went wrong'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }
}
