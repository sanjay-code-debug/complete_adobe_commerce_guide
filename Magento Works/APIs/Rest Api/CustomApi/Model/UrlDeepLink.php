<?php

/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */

namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\UrlDeeplinkInterface;
use Codilar\CustomApi\Api\Data\DeepLinkResponse\DeepLinkResponseInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Exception;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\CompositeUrlFinder;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Cms\Model\PageFactory;
use Codilar\CustomApi\Api\Data\DeepLinkResponse\DeepLinkFilterResponseInterfaceFactory;
use Amasty\ShopbySeo\Helper\UrlParser;
use Magento\Store\Api\StoreRepositoryInterface;

class UrlDeepLink implements UrlDeeplinkInterface
{
    /**
     * @var DeepLinkResponseInterfaceFactory
     */
    private DeepLinkResponseInterfaceFactory $deepLinkResponse;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CompositeUrlFinder
     */
    private CompositeUrlFinder $compositeUrlFinder;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;
    private StoreRepositoryInterface $storeRepository;

    /**
     * @var DeepLinkFilterResponseInterfaceFactory
     */
    private DeepLinkFilterResponseInterfaceFactory $deepLinkFilterResponse;

    /**
     * @var UrlParser
     */
    private UrlParser $urlParser;

    /**
     * UrlDeepLink constructor
     *
     * @param DeepLinkResponseInterfaceFactory $deepLinkResponse
     * @param LoggerInterface $logger
     * @param CompositeUrlFinder $compositeUrlFinder
     * @param StoreManagerInterface $storeManager
     * @param CategoryRepositoryInterface $categoryRepository
     * @param PageFactory $pageFactory
     * @param DeepLinkFilterResponseInterfaceFactory $deepLinkFilterResponse
     * @param UrlParser $urlParser
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        DeepLinkResponseInterfaceFactory $deepLinkResponse,
        LoggerInterface $logger,
        CompositeUrlFinder $compositeUrlFinder,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository,
        PageFactory $pageFactory,
        DeepLinkFilterResponseInterfaceFactory $deepLinkFilterResponse,
        UrlParser $urlParser,
        ProductRepositoryInterface $productRepository,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->deepLinkResponse = $deepLinkResponse;
        $this->logger = $logger;
        $this->compositeUrlFinder = $compositeUrlFinder;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->pageFactory = $pageFactory;
        $this->deepLinkFilterResponse = $deepLinkFilterResponse;
        $this->urlParser = $urlParser;
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @inheritdoc
     */
    public function urlDeepLinkValidation(string $url)
    {
        $values = str_replace('.html', '', $url);
        $parsedUrl = parse_url($url);
        $query = $parsedUrl['query'] ?? null;
        $orderList = "";
        if ($query !== null) {
            $keyValue = explode('=', $query);
            $orderList = $keyValue[1];
        }
        $values = $this->removeSortType($values);
        $values = $this->checkUrlFormat($values);
        $filters = $this->urlParser->parseSeoPart($values);
        $filtersKeyValue = [];
        foreach ($filters as $index => $filter) {
            $filtersKeyValue[$index] = $filter;
        }
        $response = $this->deepLinkResponse->create();
        try {
            $storeId = $this->storeManager->getStore()->getStoreId();

            $requestStore = null;
            $sortUrl = $this->handelSortUrl($url);
            if (!empty($sortUrl)) {
                $url = $sortUrl;
            }
            if (stripos($url, 'en-uae') !== false) {
                $parts = explode('en-uae/', $url);
                $requestStore = $this->storeRepository->get('en');
            } elseif (stripos($url, 'en-oman/') !== false) {
                $parts = explode('en-oman/', $url);
                $requestStore = $this->storeRepository->get('en_oman');
            } elseif (stripos($url, 'en-qatar/') !== false) {
                $parts = explode('en-qatar/', $url);
                $requestStore = $this->storeRepository->get('en_qatar');
            } elseif (stripos($url, 'en-bahrain/') !== false) {
                $parts = explode('en-bahrain/', $url);
                $requestStore = $this->storeRepository->get('en_bahrain');
            } elseif (stripos($url, 'en-kuwait/') !== false) {
                $parts = explode('en-kuwait/', $url);
                $requestStore = $this->storeRepository->get('en_kuwait');
            } else {
                $parts = explode('/', $url);
                $parts[0] = $url;
            }
            $pathAfterPrefix = end($parts);
            $pathAfterPrefix = preg_replace('/\/$/', '', $pathAfterPrefix);
            $data =  $this->compositeUrlFinder->findOneByData(
                [
                    UrlRewrite::REQUEST_PATH => $pathAfterPrefix,
                    UrlRewrite::STORE_ID => $storeId,
                ]
            );
            if (empty($data) && $requestStore !== null) {
                $data =  $this->compositeUrlFinder->findOneByData(
                    [
                        UrlRewrite::REQUEST_PATH => $pathAfterPrefix,
                        UrlRewrite::STORE_ID => $requestStore->getId(),
                    ]
                );
            }
            $type = $data ? $data->getEntityType() : "";
            $id = $data ? $data->getEntityId() : "";
            if ($type === "category") {
                $category = $this->categoryRepository->get($id);
                $name = $category ? $category->getName() : "";
            } elseif ($type === "cms-page") {
                $page = $this->pageFactory->create();
                $page->load($id);
                $name = $page->getTitle();
            } elseif ($type === "product") {
                $name = "";
                $type = $data ? $data->getEntityType() : "";
                $id = $data ? $data->getEntityId() : "";
                if (!empty($id)) {
                    $product = $this->productRepository->getById($id, false, $storeId);
                    $id = $product->getSku();
                    $name = $product->getName();
                }
            } else {
                $result = $this->handelPLPFilters($url, $storeId);
                $type = $result['type'];
                $name = $result['name'];
                $id = $result['id'];
            }
            $response->setType($type);
            $response->setValue($id);
            $response->setName($name);
            $response->setSortType(!empty($orderList) ? $orderList : "");
            // Use filtersKeyValue to build the response filters
            $ar = [];
            foreach ($filtersKeyValue as $key => $value) {
                $filterResponse = $this->deepLinkFilterResponse->create();
                $filterResponse->setKey($key);
                $filterResponse->setValues([$value]);
                $ar[] = $filterResponse;
            }
            $response->setFilters($ar);
            if (empty($type) && empty($name) && empty($id)) {
                throw new LocalizedException(
                    __("The url is not valid.")
                );
            }
            return $response;
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__("The url is not valid."));
        }
    }

    /**
     * Handel Sort url
     *
     * @param string $url
     * @return string
     */

    private function handelSortUrl($url)
    {
        if (strpos($url, '?product_list_order') !== false) {
            $finalString = explode("?product_list_order", $url);
            return $finalString[0];
        }
        return "";
    }

    /**
     * Handel filter in plp
     *
     * @param string $url
     * @param int $storeId
     * @return array
     */

    private function handelPLPFilters($url, $storeId)
    {
        $parts = explode('/', $url);
        $type = "";
        $name = "";
        $id = "";
        if (is_array($parts)) {
            $reverseUrl = array_reverse($parts);
            foreach ($reverseUrl as $value) {
                if (empty($value)) {
                    continue;
                }
                if (strpos($value, 'html') === false) {
                    $value .= '.html';
                }
                $data =  $this->compositeUrlFinder->findOneByData(
                    [
                        UrlRewrite::REQUEST_PATH => $value,
                        UrlRewrite::STORE_ID => $storeId,
                    ]
                );
                if ($data !== null) {
                    $type = $data ? $data->getEntityType() : "";
                    $id = $data ? $data->getEntityId() : "";
                    if (!empty($id)) {
                        try {
                            $category = $this->categoryRepository->get($id);
                            $name = $category->getName();
                        } catch (NoSuchEntityException $e) {
                            $this->logger->error($e->getMessage());
                        }
                    }
                    break;
                }
            }
        }
        return ['type' => $type, 'name' => $name , "id" => $id];
    }

    /**
     * @param $url
     * @return string
     */
    private function checkUrlFormat($url)
    {
        // Find the position of "cat" in the URL
        $catPosition = strpos($url, 'cat');
        // If "cat" is found in the URL, remove everything before it
        if ($catPosition !== false) {
            $url = substr($url, $catPosition);
        } else {
            $value = $this->getFilter($url);
            $attribute = strpos($url, $value);
            if ($attribute !== false) {
                $url = substr($url, $attribute);
            } else {
                return $url;
            }
        }
        return $url;
    }


    /**
     * @param $url
     * @return string
     */
    private function getFilter($url): string
    {
        $attributes = ['color_f', 'material_f', 'capacity','size','shape'];
        $path = parse_url($url, PHP_URL_PATH);
        $lastSegment = basename($path, ".html");
        $parts = explode('-', $lastSegment);
        $foundAttribute = ''; // Variable to store the found attribute
        foreach ($parts as $part) {
            foreach ($attributes as $attribute) {
                if (strpos($part, $attribute) === 0) {
                    $foundAttribute = $attribute;
                    break; // Exit the inner loop once attribute is found
                }
            }
            if ($foundAttribute !== '') {
                break; // Exit the outer loop once attribute is found
            }
        }
        return $foundAttribute ? $foundAttribute : $url;
    }


    /**
     * @param $url
     * @return mixed|string
     */
    private function removeSortType($url)
    {
        if (strpos($url, 'product_list_order') !== false) {
            list($base, $query) = explode('?', $url, 2);
            $queryParams = explode('&', $query);
            $queryParams = array_filter($queryParams, function ($param) {
                return strpos($param, 'product_list_order') === false;
            });
            $newQuery = implode('&', $queryParams);
            $url = $newQuery ? $base . '?' . $newQuery : $base;
        } else {
            return $url;
        }
        return $url;
    }
}
