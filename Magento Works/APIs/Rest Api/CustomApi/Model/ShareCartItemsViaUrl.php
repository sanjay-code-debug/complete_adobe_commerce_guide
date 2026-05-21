<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\ShareCartItemsViaUrlInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Exception;
use RedChamps\ShareCart\Model\ResourceModel\ShareCart\CollectionFactory as ShareCartCollectionFactory;
use Magento\Checkout\Model\CartFactory;
use RedChamps\ShareCart\Model\Share\Actions\Base;
use RedChamps\ShareCart\Model\ConfigManager;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;

class ShareCartItemsViaUrl implements ShareCartItemsViaUrlInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @var ShareCartCollectionFactory
     */
    private ShareCartCollectionFactory $shareCartCollectionFactory;

    /**
     * @var CartFactory
     */
    private CartFactory $checkoutCartFactory;

    /**
     * @var Base
     */
    private Base $base;

    /**
     * @var ConfigManager
     */
    private ConfigManager $shareCartHelper;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var EventManagerInterface
     */
    private EventManagerInterface $eventManager;

    protected $currentQuote;

    /**
     * @param CartRepositoryInterface $quoteRepository
     * @param ShareCartCollectionFactory $shareCartCollectionFactory
     * @param CartFactory $checkoutCartFactory
     * @param Base $base
     * @param ConfigManager $shareCartHelper
     * @param SerializerInterface $serializer
     * @param EventManagerInterface $eventManager
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        ShareCartCollectionFactory $shareCartCollectionFactory,
        CartFactory $checkoutCartFactory,
        Base $base,
        ConfigManager $shareCartHelper,
        SerializerInterface $serializer,
        EventManagerInterface $eventManager
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->shareCartCollectionFactory = $shareCartCollectionFactory;
        $this->checkoutCartFactory = $checkoutCartFactory;
        $this->base = $base;
        $this->shareCartHelper = $shareCartHelper;
        $this->serializer = $serializer;
        $this->eventManager = $eventManager;
    }

    /**
     * @param string $uniqueId
     * @param int $currentQuoteId
     * @return mixed
     * @throws Exception
     */
    public function getShareCartItems(string $uniqueId, int $currentQuoteId)
    {
        try {
            $fromAdmin = false;
            $sharedCart = $this->shareCartCollectionFactory->create()
                ->addFieldToFilter('unique_id', $uniqueId)
                ->addFieldToFilter('status', 'Available')
                ->load()
                ->getFirstItem();
            if ($sharedCart->getId()) {
                $cart = $this->checkoutCartFactory->create();
                $currentQuote = $this->base->getCurrentQuote($currentQuoteId);
                if ($this->shareCartHelper->getGeneralConfig('clear_cart')) {
                    $currentQuote->removeAllItems();
                }
                try {
                    $sharedQuote = $this->quoteRepository->get($sharedCart->getQuoteId());
                } catch (NoSuchEntityException $exception) {
                    return false;
                }
                $currentQuote
                    ->merge($sharedQuote)
                    ->setIsActive(true)
                    ->setSharedCartInfo($this->serializer->serialize($sharedCart->getData()));
                if (!$currentQuote->getIsVirtual() && $newShippingAddress = $currentQuote->getShippingAddress()) {
                    $oldShippingAddress = $sharedQuote->getShippingAddress();
                    $this->copyShippingInformation($newShippingAddress, $oldShippingAddress);
                }
                $this->eventManager->dispatch(
                    'share_cart_quote_copy_after',
                    ['new_quote' => $currentQuote, 'old_quote' => $sharedQuote]
                );
                $currentQuote->collectTotals();
                $this->quoteRepository->save($currentQuote);

                //To Do: remove this dirty fix to prevent error "Cart %n does not contain item %n"
                $currentQuote = $this->quoteRepository->get($currentQuote->getId());
                $cart->setQuote($currentQuote)->save();

                if (!$fromAdmin) {
                    $restoreCount = 0;
                    if ($sharedCart->getRestoreCount()) {
                        $restoreCount = $sharedCart->getRestoreCount();
                    }
                    $sharedCart->setRestoreCount($restoreCount + 1)->save();
                }
                $this->eventManager->dispatch(
                    'share_cart_restore_after',
                    [
                        'quote' => $currentQuote,
                        'shared_quote' => $sharedQuote,
                        'shared_cart' => $sharedCart,
                        'from_admin' => $fromAdmin
                    ]
                );
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom_share_cart_merge.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info($exception->getMessage());
        }
    }

    /**
     * @param $newShippingAddress
     * @param $oldShippingAddress
     * @return mixed
     */
    protected function copyShippingInformation($newShippingAddress, $oldShippingAddress)
    {
        if (!empty($oldShippingAddress->getCountryId())) {
            $newShippingAddress->setCountryId($oldShippingAddress->getCountryId());
            $newShippingAddress->setRegion($oldShippingAddress->getRegion());
            $newShippingAddress->setPostcode($oldShippingAddress->getPostcode());
            $newShippingAddress->setShippingMethod($oldShippingAddress->getShippingMethod());
            $newShippingAddress->setCollectShippingRates(true);
        }
        return $newShippingAddress;
    }
}
