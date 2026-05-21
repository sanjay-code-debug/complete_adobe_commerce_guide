<?php

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\AppWhatsAppInterface;
use Codilar\CustomApi\Api\Data\Message\ShareShoppingCartMessageInterface;
use Magento\Framework\Exception\LocalizedException;
use RedChamps\ShareCart\Model\Share\Actions\Base;
use Magento\Framework\Url;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use RedChamps\ShareCart\Model\GoogleShortner;
use RedChamps\ShareCart\Model\ConfigManager;
use Magento\Store\Model\StoreManagerInterface;

class AppWhatsApp implements AppWhatsAppInterface
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
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param Base $base
     * @param Url $url
     * @param EventManagerInterface $eventManager
     * @param GoogleShortner $googleShortnerApi
     * @param ConfigManager $shareCartHelper
     * @param ShareShoppingCartMessageInterface $message
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Base $base,
        Url $url,
        EventManagerInterface $eventManager,
        GoogleShortner $googleShortnerApi,
        ConfigManager $shareCartHelper,
        ShareShoppingCartMessageInterface $message,
        StoreManagerInterface $storeManager
    ) {

        $this->base = $base;
        $this->url = $url;
        $this->eventManager = $eventManager;
        $this->googleShortnerApi = $googleShortnerApi;
        $this->shareCartHelper = $shareCartHelper;
        $this->message = $message;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $senderName
     * @param $senderEmail
     * @param $quoteId
     * @param $customerId
     * @param $isQuoteRequest
     * @return ShareShoppingCartMessageInterface
     * @throws LocalizedException
     */
    public function appWhatsApp($senderName, $senderEmail, $quoteId, $customerId = null, $isQuoteRequest = false)
    {
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
                    "WhatsApp",
                    $customerId,
                    [],
                    $isQuoteRequest
                );
                $url = $this->getShareUrl($sharedCart);
                $domain = "https://web.whatsapp.com/send?text=";
                if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($_SERVER['HTTP_USER_AGENT'], 0, 4))) {
                    $domain = 'whatsapp://send?text=';
                }
                $result['error'] = false;
                $result['message'] = $domain . __(
                        '%1 shared his shopping cart from %2 with you.
                        Please click on link %3 to view shopping cart contents.',
                        $senderName,
                        $this->storeManager->getStore($newQuote->getStoreId())->getFrontendName(),
                        $url
                    );
                $result['quote_id'] = $sharedCart->getQuoteId();
            } else {
                $result['message'] = __("The quote doesn't exist or shopping cart is empty.");
            }
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
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
