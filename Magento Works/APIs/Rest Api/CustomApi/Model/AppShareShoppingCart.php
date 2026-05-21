<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\AppShareEmailInterface;
use Codilar\CustomApi\Api\Data\Message\ShareShoppingCartMessageInterface;
use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use RedChamps\ShareCart\Model\Share\Actions\Base;
use RedChamps\ShareCart\Model\ConfigManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\App\State;
use Magento\Backend\Model\Session\Quote as BackendQuote;
use Magento\Checkout\Model\CartFactory;
use Magento\Framework\Url;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use RedChamps\ShareCart\Model\GoogleShortner;
use RedChamps\ShareCart\Model\EmailSender;

class AppShareShoppingCart implements AppShareEmailInterface
{
    /**
     * @var Base
     */
    private Base $base;

    /**
     * @var ConfigManager
     */
    private ConfigManager $shareCartHelper;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @var State
     */
    private State $appState;

    /**
     * @var BackendQuote
     */
    private BackendQuote $backendQuote;

    /**
     * @var CartFactory
     */
    private CartFactory $checkoutCartFactory;

    /**
     * @var Url
     */
    private Url $url;

    /**
     * @var EventManagerInterface
     */
    private EventManagerInterface $eventManager;

    /**
     * @var GoogleShortner
     */
    private GoogleShortner $googleShortnerApi;

    /**
     * @var EmailSender
     */
    private EmailSender $emailSender;

    /**
     * @var ShareShoppingCartMessageInterface
     */
    private ShareShoppingCartMessageInterface $message;
    private $currentQuote = null;

    /**
     * @param Base $base
     * @param ConfigManager $shareCartHelper
     * @param CartRepositoryInterface $quoteRepository
     * @param State $appState
     * @param BackendQuote $backendQuote
     * @param CartFactory $checkoutCartFactory
     * @param Url $url
     * @param EventManagerInterface $eventManager
     * @param GoogleShortner $googleShortnerApi
     * @param EmailSender $emailSender
     * @param ShareShoppingCartMessageInterface $message
     */
    public function __construct(
        Base $base,
        ConfigManager $shareCartHelper,
        CartRepositoryInterface $quoteRepository,
        State $appState,
        BackendQuote $backendQuote,
        CartFactory $checkoutCartFactory,
        Url $url,
        EventManagerInterface $eventManager,
        GoogleShortner $googleShortnerApi,
        EmailSender $emailSender,
        ShareShoppingCartMessageInterface $message
    ) {
        $this->base = $base;
        $this->shareCartHelper = $shareCartHelper;
        $this->quoteRepository = $quoteRepository;
        $this->appState = $appState;
        $this->backendQuote = $backendQuote;
        $this->checkoutCartFactory = $checkoutCartFactory;
        $this->url = $url;
        $this->eventManager = $eventManager;
        $this->googleShortnerApi = $googleShortnerApi;
        $this->emailSender = $emailSender;
        $this->message = $message;
    }

    /**
     * @param $senderName
     * @param $senderEmail
     * @param $recipientEmail
     * @param $message
     * @param $quoteId
     * @param $customerId
     * @param $isQuoteRequest
     * @return ShareShoppingCartMessageInterface
     * @throws LocalizedException
     */
    public function sendAppEmail(
        $senderName,
        $senderEmail,
        $recipientEmail,
        $message,
        $quoteId,
        $customerId = null,
        $isQuoteRequest = false
    ) {
        $result = [];
        $result['error'] = true;
        try {
            $currentQuote = $this->base->getCurrentQuote($quoteId);
            if ($currentQuote && $currentQuote->getItemsCount()) {
                $newQuote = $this->base->copyQuote($quoteId);
                $sharedCart = $this->base->getSharedCart(
                    $newQuote,
                    $senderName,
                    $senderEmail,
                    'Email',
                    $customerId,
                    ['email' => $recipientEmail],
                    $isQuoteRequest
                );
                $shareUrlCart = $this->getShareUrl($sharedCart);
                $shareUrlCheckout = $shareUrlCart . 'checkout/1';
                $params = new DataObject(
                    [
                        'sender_name' => $senderName,
                        'sender_email' => $senderEmail,
                        'message' => $message,
                        'quote' => $newQuote,
                        'shared_cart' => $sharedCart,
                        'shared_cart_id' => $sharedCart->getId(),
                        'share_url_cart' => $shareUrlCart,
                        'share_url_checkout' => $shareUrlCheckout,
                        'recipient_email' => $recipientEmail,
                        'store' => $newQuote->getStore()
                    ]
                );

                if ($this->emailSender->sendEmail($params)) {
                    $result['error'] = false;
                    $result['message'] = __('Email has been sent successfully.');
                    $result['quote_id'] = $sharedCart->getQuoteId();
                } else {
                    $result['message'] = __('Some error occurred while sending email.');
                }
            } else {
                $result['message'] = __('The shopping cart has no items.');
            }
        } catch (Exception $e) {
            $result['message'] = __($e->getMessage());
        }
        if ($quoteId) {
            if ($result['error']) {
                throw new LocalizedException($result['message']);
            }

            $this->message->setError($result['error']);
            $this->message->setMessage($result['message']);
            $this->message->setQuoteId($result['quote_id']);
            return $this->message;
        }
        $this->message->setError($result['error']);
        $this->message->setMessage($result['message']);
        $this->message->setQuoteId($result['quote_id']);
        return $this->message;
    }

    protected function getShareUrl($sharedCart)
    {
        $params = array_merge(
            [
                'unique_id' => $sharedCart->getUniqueId(),
                '_nosid' => true
            ],
            $this->getGaParams($sharedCart)
        );
        $url = $this->url->setScope($this->getCurrentQuote()->getStore())
            ->getUrl('share_cart/action/restore', $params);
        $this->eventManager->dispatch(
            'share_cart_link_prepare_after',
            ['link' => $url, 'shared_cart' => $sharedCart]
        );
        return $this->googleShortnerApi->shortenUrl($url);
    }

    /***
     * @param $sharedCart
     * @return array
     */
    protected function getGaParams($sharedCart)
    {
        $params = [];
        if ($utmSource = $this->shareCartHelper->getGaConfig('utm_source')) {
            $params['utm_source'] = $utmSource;
        }
        $utmMedium = $this->shareCartHelper->getGaConfig('utm_medium');
        $params['utm_medium'] = $utmMedium ? $utmMedium : $sharedCart->getSharingMethod();
        if ($utmCampaign = $this->shareCartHelper->getGaConfig('utm_campaign')) {
            $params['utm_campaign'] = $utmCampaign;
        }
        return $params;
    }

    /**
     * @param $quoteId
     * @return \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentQuote($quoteId = null)
    {
        if (!$this->currentQuote) {
            if ($quoteId) {
                $this->currentQuote = $this->quoteRepository->get($quoteId);
            } else {
                $this->currentQuote = $this->appState->getAreaCode() == "adminhtml" ?
                    $this->backendQuote->getQuote() :
                    $this->checkoutCartFactory->create()->getQuote();
            }
        }
        return $this->currentQuote;
    }
}
