<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\Data\ProductDetailInterface;
use Codilar\CustomApi\Api\ShareGuestWishListInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

class ShareGuestWishList implements ShareGuestWishListInterface
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var TransportBuilder
     */
    private TransportBuilder $transportBuilder;

    /**
     * @var StateInterface
     */
    private StateInterface $inlineTranslation;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Share all wishlist products to user
     *
     * @param ProductDetailInterface[] $productDetails
     * @param string $senderName
     * @param string $recipientEmails
     * @param string $message
     * @return bool
     * @throws NoSuchEntityException|LocalizedException
     */
    public function shareGuestWishList(
        array $productDetails,
        string $senderName,
        string $recipientEmails,
        string $message
    ) {
        $emails[] = $recipientEmails;
        $emails = array_unique($emails);
        try {
            foreach ($emails as $email) {
                $transport = $this->transportBuilder->setTemplateIdentifier(
                    $this->scopeConfig->getValue(
                        'wishlist/email/email_template',
                        ScopeInterface::SCOPE_STORE
                    )
                )->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getStoreId(),
                    ]
                )->setTemplateVars(
                    [
                        'customerName' => $senderName,
                        'items' => $this->customTemplate($productDetails),
                        'viewOnSiteLink' => $this->getViewOnSiteLink($productDetails),
                        'message' => $message,
                        'store' => $this->storeManager->getStore(),
                    ]
                )->setFrom(
                    $this->scopeConfig->getValue(
                        'wishlist/email/email_identity',
                        ScopeInterface::SCOPE_STORE
                    )
                )->addTo(
                    $email
                )->getTransport();
                $transport->sendMessage();
            }
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Some issue with wishlist template'));
        }
        return true;
    }

    /**
     * Building custom template for wishlist share using wishlist collection
     *
     * @param $productDetails
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function customTemplate($productDetails)
    {
        $html = '<div>';
        $html .= '<table>';
        $html .= '<tr style="width: 100%; border-collapse: collapse">';

        $i = 0;
        $totalItems = count($productDetails);

        foreach ($productDetails as $data) {
            $product = $this->productRepository->getById($data->getProductIds());
            $productUrl = $product->getProductUrl();
            $productName = $product->getName();
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $thumbnail = $product->getThumbnail();
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
     * Get the view on site link with appended productIds
     *
     * @param array $productDetails
     * @return string
     * @throws NoSuchEntityException
     */
    private function getViewOnSiteLink(array $productDetails): string
    {
        $productIds = [];
        foreach ($productDetails as $productDetail) {
            $productIds[] = $productDetail->getProductIds();
        }
        // Construct the link with appended productIds
        $link = $this->storeManager->getStore()->getBaseUrl() . 'wishlist/guest/shared/products/';
        $link .= implode('_', $productIds);
        return $link;
    }
}
