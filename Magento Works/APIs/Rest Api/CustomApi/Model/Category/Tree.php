<?php

namespace Codilar\CustomApi\Model\Category;

use Magento\Catalog\Api\Data\CategoryTreeInterface;
use Magento\Catalog\Api\Data\CategoryTreeInterfaceFactory;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\TreeFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class Tree
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Tree
     */
    protected $categoryTree;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Collection
     */
    protected $categoryCollection;

    /**
     * @var CategoryTreeInterfaceFactory
     */
    protected $treeFactory;

    /**
     * @var TreeFactory
     */
    private $treeResourceFactory;


    /**
     * @param \Magento\Catalog\Model\ResourceModel\Category\Tree $categoryTree
     * @param StoreManagerInterface $storeManager
     * @param Collection $categoryCollection
     * @param CategoryTreeInterfaceFactory $treeFactory
     * @param TreeFactory|null $treeResourceFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\Tree $categoryTree,
        StoreManagerInterface $storeManager,
        Collection $categoryCollection,
        CategoryTreeInterfaceFactory $treeFactory,
        TreeFactory $treeResourceFactory = null
    ) {
        $this->categoryTree = $categoryTree;
        $this->storeManager = $storeManager;
        $this->categoryCollection = $categoryCollection;
        $this->treeFactory = $treeFactory;
        $this->treeResourceFactory = $treeResourceFactory ?? ObjectManager::getInstance()
            ->get(TreeFactory::class);
    }

    /**
     * Get root node by category.
     *
     * @param Category|null $category
     * @return Node|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getRootNode($category = null)
    {
        if ($category !== null && $category->getId()) {
            return $this->getNode($category);
        }

        $store = $this->storeManager->getStore();
        $rootId = $store->getRootCategoryId();

        $tree = $this->categoryTree->load(null);
        $this->prepareCollection();
        $tree->addCollectionData($this->categoryCollection);
        $root = $tree->getNodeById($rootId);
        return $root;
    }

    /**
     * Get node by category.
     *
     * @param Category $category
     * @return Node
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function getNode(Category $category)
    {
        $nodeId = $category->getId();
        $categoryTree = $this->treeResourceFactory->create();
        $node = $categoryTree->loadNode($nodeId);
        $node->loadChildren();
        $this->prepareCollection();
        $categoryTree->addCollectionData($this->categoryCollection);
        return $node;
    }

    /**
     * Prepare category collection.
     *
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function prepareCollection()
    {
        $storeId = $this->storeManager->getStore()->getId();
        $this->categoryCollection->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'is_active'
        )->setProductStoreId(
            $storeId
        )->setLoadProductCount(
            true
        )->setStoreId(
            $storeId
        );
        $this->categoryCollection->addAttributeToSelect('*');
        $this->categoryCollection->addAttributeToSelect('include_in_menu')
            ->addAttributeToFilter('is_active', true)
            ->addAttributeToFilter('include_in_menu', true)
            ->addAttributeToFilter('include_in_mbl_api', true);
    }

    /**
     * Get tree by node.
     *
     * @param Node $node
     * @param int $depth
     * @param int $currentLevel
     * @return CategoryTreeInterface
     */
    public function getTree($node, $depth = null, $currentLevel = 0)
    {
        $children = $this->getChildren($node, $depth, $currentLevel);
        /** @var CategoryTreeInterface $tree */
        $tree = $this->treeFactory->create();
        $tree->setId($node->getId())
            ->setParentId($node->getParentId())
            ->setName($node->getName())
            ->setPosition($node->getPosition())
            ->setLevel($node->getLevel())
            ->setIsActive($node->getIsActive())
            ->setProductCount($node->getProductCount())
            ->setImage($this->getCategoryImage($node))
            ->setIncludeInMenu($node->getIncludeInMenu())
            ->setChildrenData($children);
        return $tree;
    }

    /**
     * Get node children.
     *
     * @param Node $node
     * @param int $depth
     * @param int $currentLevel
     * @return CategoryTreeInterface[]|[]
     */
    protected function getChildren($node, $depth, $currentLevel)
    {
        if ($node->hasChildren()) {
            $children = [];
            foreach ($node->getChildren() as $child) {
                if ($depth !== null && $depth <= $currentLevel) {
                    break;
                }
                $children[] = $this->getTree($child, $depth, $currentLevel + 1);
            }
            return $children;
        }
        return [];
    }

    /**
     * @param $node
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCategoryImage($node)
    {
        $categoryImage = null;
        if ($node->getThumbnail()) {
            $name = basename($node->getThumbnail());
            $path = 'catalog/category/' . $name;
            $baseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $baseUrl = preg_replace('/\/en-uae\//', '/', $baseUrl);
            // $categoryImage = $baseUrl . "media/" . $path;
            $categoryImage = $baseUrl . $path . "?q=100";
        }
        return $categoryImage;
    }
}
