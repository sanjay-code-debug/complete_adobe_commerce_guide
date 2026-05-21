<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\WishListInterface;
use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Wishlist\Model\Config;
use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory as WishlistItemCollectionFactory;
use Magento\Wishlist\Model\Wishlist as ModelWishlist;
use Magento\Wishlist\Model\Wishlist\Data\WishlistItemFactory;
use Magento\Wishlist\Model\WishlistFactory;
use Psr\Log\LoggerInterface;
use Codilar\CustomApi\Model\Helper\Data;
use Magento\Wishlist\Model\Wishlist\AddProductsToWishlist;
use Magento\Wishlist\Model\Item;
use Magento\Catalog\Model\Product;
use Magento\Wishlist\Model\ResourceModel\Item\Collection;
use Codilar\CustomApi\Api\Data\WishListItemInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Codilar\CustomApi\Model\Helper\GetProductData;

class WishList implements WishListInterface
{
    /**
     * @var ModelWishlist
     */
    protected ModelWishlist $wishlist;

    /**
     * @var WishlistFactory
     */
    private $wishlistFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;
    /**
     * @var CartRepositoryInterface
     */
    protected CartRepositoryInterface $cartRepository;
    /**
     * @var CartManagementInterface
     */
    protected CartManagementInterface $cartManagement;
    /**
     * @var WishlistItemCollectionFactory
     */
    protected WishlistItemCollectionFactory $wishlistItemCollectionFactory;
    /**
     * @var Config
     */
    protected Config $wishlistConfig;
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;
    /**
     * @var Escaper
     */
    protected $escaper;
    /**
     * @var CustomerFactory
     */
    protected CustomerFactory $customerFactory;
    /**
     * @var StateInterface
     */
    protected StateInterface $inlineTranslation;
    /**
     * @var TransportBuilder
     */
    protected TransportBuilder $transportBuilder;
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var Data
     */
    private Data $data;
    /**
     * @var AddProductsToWishlist
     */
    private AddProductsToWishlist $wishlistProductAdd;

    /**
     * GetProductData
     */
    private GetProductData $getProductData;

    /**
     * @param ModelWishlist $wishlist
     * @param WishlistFactory $wishlistFactory
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     * @param CartRepositoryInterface $cartRepository
     * @param CartManagementInterface $cartManagement
     * @param WishlistItemCollectionFactory $wishlistItemCollectionFactory
     * @param Config $wishlistConfig
     * @param StoreManagerInterface $storeManager
     * @param CustomerFactory $customerFactory
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $data
     * @param AddProductsToWishlist $wishlistProductAdd
     * @param Escaper|null $escaper
     */
    public function __construct(
        ModelWishlist $wishlist,
        WishlistFactory $wishlistFactory,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        CartRepositoryInterface $cartRepository,
        CartManagementInterface $cartManagement,
        WishlistItemCollectionFactory $wishlistItemCollectionFactory,
        Config $wishlistConfig,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        Data $data,
        AddProductsToWishlist $wishlistProductAdd,
        GetProductData $getProductData,
        Escaper $escaper = null
    ) {
        $this->wishlist = $wishlist;
        $this->wishlistFactory = $wishlistFactory;
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->cartManagement = $cartManagement;
        $this->wishlistItemCollectionFactory = $wishlistItemCollectionFactory;
        $this->wishlistConfig = $wishlistConfig;
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->escaper = $escaper ?? ObjectManager::getInstance()->get(
            Escaper::class
        );
        $this->data = $data;
        $this->wishlistProductAdd = $wishlistProductAdd;
        $this->getProductData = $getProductData;
    }
    /**
     * Get wishlist
     *
     * @param int $customerId
     * @return array
     */
    public function getWishlist(int $customerId): array
    {
        try {
            $wishlist_collection = $this->wishlist->loadByCustomerId($customerId, true)->getItemCollection();
            $wishlistItem = [];
            foreach ($wishlist_collection as $item) {
                $this->populateWishListItemData($item, $wishlistItem);
            }
            return $wishlistItem;
        } catch (Exception $e) {
            return ['error' => true, 'message' => 'something went wrong'];
        }
    }

    /**
     * @inheritdoc
     */
    public function getWishlistItemsData(SearchCriteriaInterface $searchCriteria)
    {
        $wishlistItem = [];
        $productResult = $this->productRepository->getList($searchCriteria);
        try {
            if ($productResult->getTotalCount() > 0) {
                foreach ($productResult->getItems() as $item) {
                    $this->populateWishListItemData($item, $wishlistItem);
                }
            } else {
                return $wishlistItem;
            }
        } catch (Exception $e) {
            return ['error' => true, 'message' => 'something went wrong'];
        }
        return $wishlistItem;
    }

    /**
     *  Populate wishlist item details
     *
     * @param DataObject $item
     * @param array $wishListItemArray
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function populateWishListItemData($item, &$wishListItemArray): array
    {
        $storeId = $this->storeManager->getStore()->getId();
        $product = null;
        if ($item instanceof Item) {
            $productId = $item->getProductId();
            $product = $this->productRepository->getById($productId, false, $storeId);
//                $product = $this->productRepository->get($item->getProduct()->getSku());
        } elseif ($item instanceof Product) {
            $product = $item;
        }
        if ($product !== null) {
            $description = $this->getProductData->getPdpDescription(
                $product->getSku(),
                GetProductData::DESCRIPTION,
                $storeId
            );
            $imageUrl = $product->getThumbnail();
            $productName = $product->getName();
            $productSku = $product->getSku();
            $productId = $product->getId();
            if ($item instanceof Item) {
                $wishlistItemId = $item->getWishlistItemId();
            } else {
                $wishlistItemId = "";
            }
            $specialPrice = $product->getData('special_price');
            $special_from_date = $product->getData('special_from_date');
            $special_to_date = $product->getData('special_to_date');
            $originalPrice = $product->getData('price');
            $salePrice = $this->data->specialPriceWislist(
                $specialPrice,
                $special_from_date,
                $special_to_date
            );
            if ($item instanceof Item) {
                $qty = $item->getQty();
            } else {
                $qty = 0.0;
            }
            $wishlistItem['image'] = $imageUrl;
            $wishlistItem['productName'] = $productName;
            $wishlistItem['productSku'] = $productSku;
            $wishlistItem['productId'] = $productId;
            $wishlistItem['WishlistItemId'] = $wishlistItemId;
            $wishlistItem['price'] = (int)$originalPrice;
            $wishlistItem['salePrice'] = (int)$salePrice;
            $wishlistItem['qty'] = $qty;
            $wishlistItem['description'] = ucwords(strtolower($description));
            $wishListItemArray[] = $wishlistItem;
            return $wishListItemArray;
        }
        return $wishListItemArray[] = [];
    }
    /**
     * @inheritdoc
     */
    public function save($customerId, $productId)
    {
        try {
            $storeId = $this->storeManager->getStore()->getId();
            $product = $this->productRepository->getById($productId, false, $storeId);
            /** @var \Magento\Wishlist\Model\Wishlist $wishlist */
            $wishlist = $this->wishlistFactory->create();
            $wishlist->loadByCustomerId($customerId, true);
            $wishlist->addNewItem($product, $buyRequest = null);
            $wishlist->save();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Item can not be add to wishlist'));
        }
        return true;
    }
    /**
     * @inheritdoc
     */
    public function delete($customerId, $productId)
    {
        try {
            $wishListItems = $this->getWishlistCollection($customerId);
            /** @var \Magento\Wishlist\Model\Item $item */
            foreach ($wishListItems as $item) {
                if ($item->getProductId() == $productId) {
                    $item->delete();
                    $wishListItems->save();
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Item can not delete from wish list'));
        }
        return true;
    }
    /**
     * @inheritdoc
     */
    public function clearALL($customerId)
    {
        try {
            $wishListItems = $this->getWishlistCollection($customerId);
            /** @var \Magento\Wishlist\Model\Item $item */
            foreach ($wishListItems as $item) {
                $item->delete();
                $wishListItems->save();
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Can not delete all the items from wish list'));
        }
        return true;
    }
    /**
     * Item collection
     *
     * @param int $customerId
     * @return \Magento\Wishlist\Model\ResourceModel\Item\Collection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getWishlistCollection(int $customerId)
    {
        $wish = $this->wishlistFactory->create()->loadByCustomerId($customerId);
        return $wish->getItemCollection();
    }
    /**
     * @inheritdoc
     */
    public function moveToCart($customerId, $productId, $qty)
    {
        try {
            $storeId = $this->storeManager->getStore()->getId();
            $product = $this->productRepository->getById($productId, false, $storeId);
            $cart = $this->cartRepository->getForCustomer($customerId);
            if (!$cart->getId()) {
                $cart = $this->cartManagement->createEmptyCart();
                if ($customerId) {
                    $cart->setCustomerId($customerId);
                }
            }
            $cart->addProduct($product, $qty);
            $this->cartRepository->save($cart);
            if ($customerId && !$cart->getCustomerId()) {
                $cart->setCustomerId($customerId);
                $this->cartRepository->save($cart);
            }
            $this->delete($customerId, $productId);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Can not move items from wishlist to cart'));
        }
        return true;
    }
    /**
     * @inheritdoc
     */
    public function allToCart($customerId)
    {
        try {
            $storeId = $this->storeManager->getStore()->getId();
            $wishlist = $this->wishlist->loadByCustomerId($customerId, true);
            $wishlistId = $wishlist->getId();
            $wishlist = $this->wishlist->load($wishlistId);
            $wishlistItems = $wishlist->getItemCollection();
            $cart = $this->cartRepository->getForCustomer($customerId);
            if (!$cart->getId()) {
                $cart = $this->cartManagement->createEmptyCart();
                if ($customerId) {
                    $cart->setCustomerId($customerId);
                }
            }
            foreach ($wishlistItems as $item) {
                $productId = $item->getProduct()->getId();
                $product = $this->productRepository->getById($productId, false, $storeId);
                $qty = $item->getQty();
                $cart->addProduct($product, $qty);
            }
            $this->cartRepository->save($cart);
            if ($customerId && !$cart->getCustomerId()) {
                $cart->setCustomerId($customerId);
                $this->cartRepository->save($cart);
            }
            $this->removeProductsFromWishlist($wishlistId);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Can not add all items from wishlist to cart'));
        }
        return true;
    }
    /**
     * Delete item from wishlist
     *
     * @param int $wishlistId
     * @return void
     */
    public function removeProductsFromWishlist($wishlistId)
    {
        $wishlist = $this->wishlistFactory->create()->load($wishlistId);
        $wishlistItems = $this->wishlistItemCollectionFactory->create()
            ->addWishlistFilter($wishlist)
            ->getItems();
        foreach ($wishlistItems as $item) {
            $item->delete();
        }
    }
    /**
     * @inheritdoc
     */
    public function shareWishListDetails(int $customerId, string $recipientEmails, string $message)
    {
        $wishlist = $this->wishlist->loadByCustomerId($customerId, true);
        $wishlistId = $wishlist->getId();
        $wishlist = $this->wishlist->load($wishlistId);
        $wishlistItems = $wishlist->getItemCollection();
        $sharingLimit = $this->wishlistConfig->getSharingEmailLimit();
        $textLimit = $this->wishlistConfig->getSharingTextLimit();
        $emailsLeft = $sharingLimit - $wishlist->getShared();
        $emails = $recipientEmails;
        $emails = empty($emails) ? $emails : explode(',', $emails);
        $error = false;
        $message = (string)$message;
        if (strlen($message) > $textLimit) {
            $error = 'Message length must not exceed ' . $textLimit;
        } else {
            $message = nl2br($this->escaper->escapeHtml($message));
            if (empty($emails)) {
                $error = 'Please enter an email address.';
            } else {
                if (count($emails) > $emailsLeft) {
                    $error = 'Maximum of emails can be sent.' . $emailsLeft;
                } else {
                    foreach ($emails as $index => $email) {
                        $email = trim($email);
                        // @codingStandardsIgnoreLine
                        if (!\Zend_Validate::is($email, \Magento\Framework\Validator\EmailAddress::class)) {
                            $error = 'Please enter a valid email address.';
                            break;
                        }
                        $emails[$index] = $email;
                    }
                }
            }
        }
        if ($error) {
            throw new LocalizedException(__($error));
        }
        $sent = 0;
        try {
            $storeId = $this->storeManager->getStore()->getStoreId();
            $customer = $this->customerFactory->create();
            $customer->load($customerId);
            $customerData = $customer->getData();
            $customerName = $customer->getFirstname();
            $emails = array_unique($emails);
            $sharingCode = $wishlist->getSharingCode();
            try {
                foreach ($emails as $email) {
                    $transport = $this->transportBuilder->setTemplateIdentifier(
                        $this->scopeConfig->getValue(
                            'wishlist/email/email_template',
                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                            $storeId
                        )
                    )->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => $storeId,
                        ]
                    )->setTemplateVars(
                        [
                            'customerName' => $customerName,
                            'salable' => $wishlist->isSalable() ? 'yes' : '',
                            'items' => $this->customTemplate($wishlistItems),
                            'viewOnSiteLink' => $this->storeManager->getStore()->getBaseUrl() .
                                'wishlist/shared/index/code/' . $sharingCode,
                            'message' => $message,
                            'store' => $this->storeManager->getStore(),
                        ]
                    )->setFrom(
                        $this->scopeConfig->getValue(
                            'wishlist/email/email_identity',
                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                            $storeId
                        )
                    )->addTo(
                        $email
                    )->getTransport();
                    $transport->sendMessage();
                    $sent++;
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                throw new LocalizedException(__('Some issue with wishlist template'));
            }
            $wishlist->setShared($wishlist->getShared() + $sent);
            $wishlist->save();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Some issue with you wishlist details'));
        }
        return true;
    }
    /**
     * Building custom template for wishlist share using wishlist collection
     *
     * @param $wishListCollection
     * @param Collection $wishListCollection
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function customTemplate($wishListCollection)
    {
        $html = '<div>';
        $html .= '<table>';
        $html .= '<tr style="width: 100%; border-collapse: collapse">';

        $i = 0;
        $totalItems = count($wishListCollection);

        foreach ($wishListCollection as $item) {
            $productUrl = $item->getProductUrl();
            $productName = $item->getProduct()->getName();
            $productDescription = $item->getDescription(); // Assuming there's a getDescription() method
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $thumbnail = $item->getProduct()->getThumbnail();
            $imageUrl = $mediaUrl . 'catalog/product' . $thumbnail;

            $html .= '<td class="col product" style="width: 33.33%">';
            $html .= '<p style="text-align: center;">';
            $html .= '<a href="' . $productUrl . '">';
            $html .= '<img src="' . $imageUrl . '" alt="' . $productName . '" style="width: 100px; height: 100px;">';
            $html .= '</a>';
            $html .= '</p>';
            $html .= '<p style="text-align: center;">';
            $html .= '<a href="' . $productUrl . '"><strong>' . $productName . '</strong></a>';
            $html .= '</p>';

            if (!empty($productDescription)) {
                $html .= '<p style="text-align: center;"><strong>Comment</strong><br/>' . $productDescription . '</p>';
            }

            $html .= '<p style="text-align: center;"><a href="' . $productUrl . '">' . __('View Product') . '</a></p>';
            $html .= '</td>';

            $i++;

            if ($i % 3 == 0 && $i < $totalItems) {
                $html .= '</tr><tr><td colspan="5">&nbsp;</td></tr><tr>';
            }
        }

        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @inheritdoc
     */

    public function addItemsToCartGuest(array $cartItems)
    {
        $quote = null;
        $quoteItems = null;
        foreach ($cartItems as $item) {
            if ($quote === null && $quoteItems === null) {
                $quoteId = $item->getQuoteId();
                try {
                    $quote = $this->cartRepository->getActive($quoteId);
                    $quoteItems = $quote->getItems();
                } catch (NoSuchEntityException $e) {
                    $this->logger->error($e->getMessage());
                    $quote = null;
                    throw new LocalizedException(__('Can not add all items from wishlist to cart'));
                }
            }
            $quoteItems[] = $item;
        }
        if ($quote !== null && is_array($quoteItems) && count($quoteItems) > 0) {
            $quote->setItems($quoteItems);
            $this->cartRepository->save($quote);
            $quote->collectTotals();
            return true;
        }
        return false;
    }

    public function addProductsToWishList(int $customerId, array $wishListItems)
    {
        $wishlist = $this->wishlist->loadByCustomerId($customerId, true);
        $wishlistItems = [];
        foreach ($wishListItems as $wishlistItem) {
            $wishlistArgs['id'] = $wishlistItem->getId();
            $wishlistArgs['quantity'] = $wishlistItem->getQuantity();
            if (!empty($wishlistItem->getParentSku())) {
                $wishlistArgs['parent_sku'] = $wishlistItem->getParentSku();
            }
            if (!empty($wishlistItem->getSku())) {
                $wishlistArgs['sku'] = $wishlistItem->getSku();
            }
            if (!empty($wishlistItem->getDescription())) {
                $wishlistArgs['description'] = $wishlistItem->getDescription();
            }
            $wishlistItems[] = (new WishlistItemFactory())->create($wishlistArgs);
        }
        try {
            $wishlistResult = $this->wishlistProductAdd->execute($wishlist, $wishlistItems);
        } catch (AlreadyExistsException $e) {
            return false;
        }
        return count($wishlistResult->getErrors()) <= 0;
    }
}
