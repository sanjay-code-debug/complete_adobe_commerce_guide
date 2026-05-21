<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Plugin;

use Exception;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface as MagentoRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface as PsrLogger;
use Magento\Cms\Model\Template\FilterProvider;
use Codilar\CustomApi\Model\Helper\GetSalableQty;
use Magento\Catalog\Helper\Image as ImageHelper;
use Codilar\CustomApi\Model\Helper\Data;
use Codilar\CustomApi\Model\Helper\GetProductData;

class ModifyPdpPage
{
    /**
     * Declare const
     */
    public const POSTPAY = 'postpay';

    /**
     * Declare const
     */
    public const TABBY = 'tabby';

    /**
     * Get config search url value
     */
    public const KLEVU_SEARCH_URL = "klevu_search/general/cloud_search_v2_url";

    /**
     * Get config api key value
     */
    public const KLEVU_API_KEY = "klevu_search/general/js_api_key";

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var PsrLogger
     */
    protected PsrLogger $logger;

    /**
     * Declare const
     */
    public const DESCRIPTION = 'description';

    /**
     * @var FilterProvider
     */
    private FilterProvider $filterProvider;

    /**
     * @var GetSalableQty
     */
    private GetSalableQty $getSalableQty;
    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var Data
     */
    private Data $data;

    /**
     * @var GetProductData
     */
    private GetProductData $getProductData;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param PsrLogger $logger
     * @param FilterProvider $filterProvider
     * @param GetSalableQty $getSalableQty
     * @param ImageHelper $imageHelper
     * @param Data $data
     * @param GetProductData $getProductData
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        PsrLogger $logger,
        FilterProvider $filterProvider,
        GetSalableQty $getSalableQty,
        ImageHelper $imageHelper,
        Data $data,
        GetProductData $getProductData
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->filterProvider = $filterProvider;
        $this->getSalableQty = $getSalableQty;
        $this->imageHelper = $imageHelper;
        $this->data = $data;
        $this->getProductData = $getProductData;
    }

    /**
     * After get
     *
     * @param MagentoRepository $subject
     * @param ProductInterface $product
     * @return ProductInterface
     * @throws Exception
     */
    public function afterGet(MagentoRepository $subject, ProductInterface $product)
    {
        $this->updateMediaGalleryWithCachedImages($product);
        $customAttributes = $product->getCustomAttributes();
        foreach ($customAttributes as $attribute) {
            if ($attribute->getAttributeCode() === 'show_dimensions') {
                $value = $attribute->getValue();
                $withoutUlTags = str_replace(['<ul>', '</ul>', "\r\n"], '', $value);
                $replacedTags = str_replace(['<li>', '</li>', '<strong>'], ['<p>', '</p>','<strong style="font-family: Lato-Bold, sans-serif; font-size: 13px;">'], $withoutUlTags);
                $replacedPTags = str_replace(['<p>'], ['<p style="font-family: Lato-Light, sans-serif; font-size: 13px;">  &bull; '], $replacedTags);
                $appendBrTag = str_replace(['</div>'], ['</div><br>'], $replacedPTags);
                // Remove <br> tag only from the last </div>
                $appendBrTag = preg_replace('/<\/div><br>(?!.*?<\/div><br>)/s', '</div>', $appendBrTag, 1);
                $attribute->setValue($appendBrTag);
            }
        }
        $isPostAvailable = $this->getProductData->isPaymentAvailable($product->getFinalPrice(), self::POSTPAY);
        $isTabbyAvailable = $this->getProductData->isPaymentAvailable($product->getFinalPrice(), self::TABBY);
        $onlyQtyLeft = $this->data->getQtyLeft($product);
        $klevu_search_url = $this->getKlevuSearchUrl();
        $klevu_api_key = $this->getKlevuApiKey();
        $pdp_description = $this->getPdpDescription($product, self::DESCRIPTION);
        $attributeCodeValue = $this->data->getProductWarningMessage($product);
        $message = $this->data->getMessageUsingAttributeId($attributeCodeValue);
        $getAvailableQty = $this->getSalableQty->getSalableQty($product->getSku());
        $extensionAttributeMessage = $product->getExtensionAttributes()->setWpWarningMessage($message);
        $product->setExtensionAttributes($extensionAttributeMessage);
        if ($isPostAvailable['enable'] && $isPostAvailable['price']) {
            $extensionAttributePostPay = $product->getExtensionAttributes()->setIsPostpayAvailable('1');
        } else {
            $extensionAttributePostPay = $product->getExtensionAttributes()->setIsPostpayAvailable('0');
        }
        $product->setExtensionAttributes($extensionAttributePostPay);

        if ($isTabbyAvailable['enable'] && $isTabbyAvailable['price']) {
            $extensionAttributeTabby = $product->getExtensionAttributes()->SetIsTabbyAvailable('1');
        } else {
            $extensionAttributeTabby = $product->getExtensionAttributes()->SetIsTabbyAvailable('0');
        }
        $product->setExtensionAttributes($extensionAttributeTabby);

        $extensionAttributeApplePayEnable = $product->getExtensionAttributes()
            ->setIsApplepayEnable(
                $this->getProductData->isPaymentAvailable(0, 'applepay')['enable']
            );
        $product->setExtensionAttributes($extensionAttributeApplePayEnable);

        $extensionAttributeCardPaymentEnable = $product->getExtensionAttributes()
            ->setIsCardpaymentEnble(
                $this->getProductData->isPaymentAvailable(0, 'cardpayment')['enable']
            );
        $product->setExtensionAttributes($extensionAttributeCardPaymentEnable);

        $extensionAttributeLeftQty = $product->getExtensionAttributes()->setOnlyQtyLeft($onlyQtyLeft);
        $product->setExtensionAttributes($extensionAttributeLeftQty);
        $extensionAttributeKlevuSearchUrl = $product->getExtensionAttributes()->setKlevuSearchUrl($klevu_search_url);
        $product->setExtensionAttributes($extensionAttributeKlevuSearchUrl);
        $extensionAttributeKlevuApiKey = $product->getExtensionAttributes()->setKlevuApiKey($klevu_api_key);
        $product->setExtensionAttributes($extensionAttributeKlevuApiKey);
        $extensionAttributePdpDescription = $product->getExtensionAttributes()->setPdpDescription(ucwords(strtolower($pdp_description)));
        $product->setExtensionAttributes($extensionAttributePdpDescription);
        $extensionAttributeAvailableStock = $product->getExtensionAttributes()->setAvailableStock($getAvailableQty);
        $product->setExtensionAttributes($extensionAttributeAvailableStock);
        return $product;
    }

    /**
     * @param $product
     * @param $type
     * @return string|void|null
     */
    public function getPdpDescription($product, $type)
    {
        if ($type == self::DESCRIPTION) {
            $attributeNames = ['material_f', 'capacity', 'size', 'shape', 'color_f'];
            $descriptionParts = [];

            foreach ($attributeNames as $attributeName) {
                $attribute = $product->getCustomAttribute($attributeName);
                $attributeValue = $attribute ? $attribute->getValue() : null;
                $attributeOptions = $product->getResource()->getAttribute($attributeName)->getOptions();
                $attributeValueName = $this->getOptionName($attributeOptions, $attributeValue);

                if (!empty($attributeValueName) && $attributeValueName !== " ") {
                    $descriptionParts[] = $attributeValueName;
                }
            }

            if (!empty($descriptionParts)) {
                return implode(' | ', $descriptionParts);
            } else {
                return "No description available.";
            }
        }
    }

    /**
     * Get option name
     *
     * @param $options
     * @param $capacity
     * @return mixed
     */
    public function getOptionName($options, $capacity)
    {
        try {
            $value = null;
            foreach ($options as $option) {
                if ($option->getValue() == $capacity) {
                    $value = $option->getLabel();
                    break;
                }
            }
            return $value;
        } catch (Exception $e) {
            $this->logger->error('Pdp page description error - ' . $e->getMessage());
        }
    }

    /**
     * Get config value
     *
     * @return mixed
     */
    public function getKlevuSearchUrl()
    {
        try {
            $pre = 'https://';
            $post = '/cs/v2/search';
            $value =  $this->scopeConfig->getValue(
                self::KLEVU_SEARCH_URL,
                ScopeInterface::SCOPE_STORE
            );
            $url = $pre . $value . $post;
            if ($value) {
                return $url;
            } else {
                return null;
            }
        } catch (Exception $e) {
            $this->logger->error(' PDP page error for get search  url  - ' . $e->getMessage());
        }
    }

    /**
     * Get config value
     *
     * @return mixed
     */
    public function getKlevuApiKey()
    {
        try {
            $key =  $this->scopeConfig->getValue(
                self::KLEVU_API_KEY,
                ScopeInterface::SCOPE_STORE
            );
            if ($key) {
                return $key;
            } else {
                return null;
            }
        } catch (Exception $e) {
            $this->logger->error(' PDP page error for get api  key - ' . $e->getMessage());
        }
    }

    protected function updateMediaGalleryWithCachedImages(ProductInterface $product)
    {
        $mediaGalleryEntries = $product->getMediaGalleryEntries();

        foreach ($mediaGalleryEntries as $entry) {
            $imageUrl = $this->imageHelper
                ->init($product, 'product_small_image')
                ->setImageFile($entry->getFile())
                ->getUrl();
            $base_path = "media/catalog/product";
            $start_pos = strpos($imageUrl, $base_path);
            if ($start_pos !== false) {
                // Remove the identified part
                $imageUrl = substr($imageUrl, $start_pos + strlen($base_path) + 1);
                $imageUrl = "/" . $imageUrl;
            }
            $entry->setUrl($imageUrl);
            $entry->setFile($imageUrl);
        }

        $product->setMediaGalleryEntries($mediaGalleryEntries);

        return $product;
    }
}
