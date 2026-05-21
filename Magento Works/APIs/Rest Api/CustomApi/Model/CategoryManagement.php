<?php

namespace Codilar\CustomApi\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class CategoryManagement extends \Magento\Catalog\Model\CategoryManagement
    implements \Codilar\CustomApi\Api\CategoryManagementInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Codilar\CustomApi\Model\Category\Tree
     */
    protected $categoryTree;

    /**
     * @var \Magento\Framework\App\ScopeResolverInterface
     */
    private $scopeResolver;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $categoriesFactory;

    /**
     * CategoryManagement constructor.
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Codilar\CustomApi\Model\Category\Tree $categoryTree
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoriesFactory
     */
    public function __construct(
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Codilar\CustomApi\Model\Category\Tree $categoryTree,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoriesFactory
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryTree = $categoryTree;
        $this->categoriesFactory = $categoriesFactory;
    }
}
