<?php

namespace Codilar\CustomApi\Api\Data;

use Codilar\PushNotification\Api\Data\PushNotificationResponseFormatInterface;
use Codilar\PushNotification\Api\Data\PushNotificationResponseInterface;

interface CountryModelInterface
{
    /**
     * Set three letter abbreviation
     *
     * @param string $threeLetterAbbreviation
     * @return $this
     */
    public function setThreeLetterAbbreviation(string $threeLetterAbbreviation);

    /**
     * Set two letter abbreviation
     *
     * @param string $twoLetterAbbreviation
     * @return $this
     */
    public function setTwoLetterAbbreviation(string $twoLetterAbbreviation);


    /**
     * Get three letter abbreviation
     *
     * @return string
     */
    public function getThreeLetterAbbreviation();
    /**
     * Get two letter abbreviation
     *
     * @return string
     */
    public function getTwoLetterAbbreviation();

    /**
     * Set available store views
     *
     * @param \Magento\Store\Api\Data\StoreInterface $availableStoreViews
     * @return $this
     */
    public function setAvailableStoreViews(array $availableStoreViews);

    /**
     * Get available store views
     *
     * @return \Magento\Store\Api\Data\StoreInterface[]
     */
    public function getAvailableStoreViews();

    /**
     * Set Default store view code
     *
     * @param string $defaultStoreViewCode
     * @return $this
     */
    public function setDefaultStoreViewCode(string $defaultStoreViewCode);

    /**
     * Get Default store view code
     *
     * @return string
     */
    public function getDefaultStoreViewCode();

    /**
     * Set Country Image
     *
     * @param string $countryImage
     * @return $this
     */
    public function setCountryImage(string $countryImage);

    /**
     * Get Country Image
     *
     * @return string
     */
    public function getCountryImage();

    /**
     * Set full name En
     *
     * @param string $fullNameEn
     * @return $this
     */
    public function setFullNameEn(string $fullNameEn);

    /**
     * Get Full Name En
     *
     * @return string
     */
    public function getFullNameEn();

    /**
     * Set full name En
     *
     * @param string $fullNameAr
     * @return $this
     */
    public function setFullNameAr(string $fullNameAr);
    /**
     * Get Full Name Ar
     *
     * @return string
     */
    public function getFullNameAr();
    /**
     * Get currency code
     *
     * @return string
     */
    public function getCountryCurrency();

    /**
     * Set currency code
     *
     * @param string $currencyCode
     * @return string
     */
    public function setCountryCurrency(string $currencyCode);

    /**
     * Get push notification response
     *
     * @return \Codilar\PushNotification\Api\Data\PushNotificationResponseInterface[]
     */
    public function getPushNotificationResponses();

    /**
     * Set push notification response
     *
     * @param \Codilar\PushNotification\Api\Data\PushNotificationResponseInterface[] $push_notification_responses
     * @return $this
     */
    public function setPushNotificationResponses(
        array $push_notification_responses
    );
    /**
     * Set klevu models
     *
     * @param \Codilar\CustomApi\Api\Data\KlevuModelInterface[] $klevuModels
     * @return $this
     */
    public function setKlevuModels(array $klevuModels);

    /**
     * Get klevu models
     *
     * @return \Codilar\CustomApi\Api\Data\KlevuModelInterface[]
     */
    public function getKlevuModels();
    /**
     * Set Base models
     *
     * @param \Codilar\CustomApi\Api\Data\BaseUrlModelInterface[] $baseModels
     * @return $this
     */
    public function setBaseModels(array $baseModels);
    /**
     * Get Base models
     *
     * @return \Codilar\CustomApi\Api\Data\BaseUrlModelInterface[]
     */
    public function getBaseModels();
    /**
     * Get adjust models
     *
     * @return \Codilar\CustomApi\Api\Data\AdjustModelInterface[]
     */
    public function getAdjustModels();
    /**
     * Set adjust models
     *
     * @param \Codilar\CustomApi\Api\Data\AdjustModelInterface[] $adjustModels
     * @return $this
     */
    public function setAdjustModels(array $adjustModels);
}
