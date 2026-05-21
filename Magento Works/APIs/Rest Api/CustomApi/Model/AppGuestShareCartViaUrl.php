<?php

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\AppShareCartViaUrlInterface;
use Codilar\CustomApi\Api\Data\Message\ShareShoppingCartMessageInterface;
use Magento\Framework\Exception\LocalizedException;
use RedChamps\ShareCart\Model\Share\Actions\Base;
use Magento\Framework\Url;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use RedChamps\ShareCart\Model\GoogleShortner;
use RedChamps\ShareCart\Model\ConfigManager;

class AppGuestShareCartViaUrl implements AppShareCartViaUrlInterface
{

    /**
     * @var Base
     */
    private Base $base;

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
     * @var ConfigManager
     */
    private ConfigManager $shareCartHelper;

    /**
     * @var ShareShoppingCartMessageInterface
     */
    private ShareShoppingCartMessageInterface $message;

    /**
     * @param Base $base
     * @param Url $url
     * @param EventManagerInterface $eventManager
     * @param GoogleShortner $googleShortnerApi
     * @param ConfigManager $shareCartHelper
     * @param ShareShoppingCartMessageInterface $message
     */

    public function __construct(
        Base $base,
        Url $url,
        EventManagerInterface $eventManager,
        GoogleShortner $googleShortnerApi,
        ConfigManager $shareCartHelper,
        ShareShoppingCartMessageInterface $message
    ) {

        $this->base = $base;
        $this->url = $url;
        $this->eventManager = $eventManager;
        $this->googleShortnerApi = $googleShortnerApi;
        $this->shareCartHelper = $shareCartHelper;
        $this->message = $message;
    }

    /**
     * @param string $quoteId
     * @return ShareShoppingCartMessageInterface
     * @throws LocalizedException
     */
    public function shareCartViaUrl(string $quoteId)
    {
        $isQuoteRequest = true;
        $senderName = null;
        $customerId = null;
        $senderEmail = null;

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
                    'URL',
                    $customerId,
                    [],
                    $isQuoteRequest
                );
                $url = $this->getShareUrl($sharedCart);
                $result['error'] = false;
                $result['message'] = $url;
                $result['shared_cart'] = $sharedCart->getData();
                $result['quote_id'] = $sharedCart->getQuoteId();
            } else {
                $result['message'] = __("The quote doesn't exist or shopping cart is empty.");
            }
        } catch (\Exception $e) {
            $result['message'] = __($e->getMessage());
        }
        if ($quoteId) {
            if ($result['error']) {
                throw new LocalizedException($result['message']);
            }
            $this->message->setError($result['error']);
            $this->message->setMessage($result['message']);
            $this->message->setSharedCart($result['shared_cart']);
            $this->message->setQuoteId($result['quote_id']);
            return $this->message;
        }
        $this->message->setError($result['error']);
        $this->message->setMessage($result['message']);
        $this->message->setSharedCart($result['shared_cart']);
        $this->message->setQuoteId($result['quote_id']);
        return $this->message;
    }
    /**
     * @param $sharedCart
     * @return mixed
     */
    protected function getShareUrl($sharedCart)
    {
        $params = array_merge(
            [
                'unique_id' => $sharedCart->getUniqueId(),
                '_nosid' => true
            ],
            $this->getGaParams($sharedCart)
        );
        $url = $this->url->setScope($this->base->getCurrentQuote()->getStore())
            ->getUrl('share_cart/action/restore', $params);
        $this->eventManager->dispatch(
            'share_cart_link_prepare_after',
            ['link' => $url, 'shared_cart' => $sharedCart]
        );
        return $this->googleShortnerApi->shortenUrl($url);
    }

    /**
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
}
