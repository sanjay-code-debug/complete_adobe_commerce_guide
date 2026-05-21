<?php
/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */
namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\CustomCartInterface;
use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Wishlist\Model\WishlistFactory;
use Psr\Log\LoggerInterface;

class Cart implements CustomCartInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var WishlistFactory
     */
    private $wishlistFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var QuoteFactory
     */
    private QuoteFactory $quoteFactory;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param WishlistFactory $wishlistFactory
     * @param LoggerInterface $logger
     * @param QuoteFactory $quoteFactory
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        WishlistFactory $wishlistFactory,
        LoggerInterface $logger,
        QuoteFactory $quoteFactory,
        CartRepositoryInterface $cartRepository
    ) {
        $this->productRepository = $productRepository;
        $this->wishlistFactory = $wishlistFactory;
        $this->logger = $logger;
        $this->quoteFactory = $quoteFactory;
        $this->cartRepository = $cartRepository;
    }

    /**
     * Move cart items to wishlist
     *
     * @param int $customerId
     * @param string $sku
     * @param int $qty
     * @return boolean
     * @throws Exception
     */
    public function moveToWishlist($customerId, $sku, $qty)
    {
        try {
            $product = $this->productRepository->get($sku);
            $productId = $product->getId();
            $wishlist = $this->wishlistFactory->create();
            $wishlist->loadByCustomerId($customerId, true);
            $wishlist->addNewItem($product, $qty);
            $wishlist->save();
            $this->deleteMovedItemFromCart($customerId, $productId);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Item can not be move from cart to wishlist'));
        }
        return true;
    }

    /**
     * Delete product from cart after move to wishlist
     *
     * @param  $customerId
     * @param  $productId
     * @return void
     */
    private function deleteMovedItemFromCart($customerId, $productId)
    {
        try {
            $quote = $this->quoteFactory->create();
            $cart = $this->cartRepository->getActiveForCustomer($customerId);
            $cartId = $cart->getId();
            $quote->loadActive($cartId);
            foreach ($quote->getAllItems() as $item) {
                if ($item->getProductId() == $productId) {
                    $quote->removeItem($item->getId());
                    break;
                }
            }
            $quote->collectTotals()->save();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
