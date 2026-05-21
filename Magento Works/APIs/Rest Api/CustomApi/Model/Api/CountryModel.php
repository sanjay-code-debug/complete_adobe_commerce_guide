<?php

namespace Codilar\CustomApi\Model\Api;

use Codilar\CustomApi\Api\Data\CountryModelInterface;
use Codilar\PushNotification\Api\Data\PushNotificationResponseFormatInterface;
use Magento\Framework\DataObject;

class CountryModel extends DataObject implements CountryModelInterface
{

    /**
     * @inheritdoc
     */
    public function setThreeLetterAbbreviation(string $threeLetterAbbreviation)
    {
        return $this->setData("three_letter_abbreviation", $threeLetterAbbreviation);
    }
    /**
     * @inheritdoc
     */
    public function getThreeLetterAbbreviation()
    {
        return $this->getData('three_letter_abbreviation');
    }
    /**
     * @inheritdoc
     */
    public function setAvailableStoreViews(array $availableStoreViews)
    {
        return $this->setData("available_store_views", $availableStoreViews);
    }
    /**
     * @inheritdoc
     */
    public function getAvailableStoreViews()
    {
        return $this->getData('available_store_views');
    }
    /**
     * @inheritdoc
     */
    public function setDefaultStoreViewCode(string $defaultStoreViewCode)
    {
        return $this->setData("default_store_view_code", $defaultStoreViewCode);
    }
    /**
     * @inheritdoc
     */
    public function getDefaultStoreViewCode()
    {
        return $this->getData('default_store_view_code');
    }
    /**
     * @inheritdoc
     */
    public function setCountryImage(string $countryImage)
    {
        return $this->setData("country_image", $countryImage);
    }
    /**
     * @inheritdoc
     */
    public function getCountryImage()
    {
        return $this->getData('country_image');
    }
    /**
     * @inheritdoc
     */
    public function setFullNameEn(string $fullNameEn)
    {
        return $this->setData("full_name_en", $fullNameEn);
    }
    /**
     * @inheritdoc
     */
    public function getFullNameEn()
    {
        return $this->getData('full_name_en');
    }
    /**
     * @inheritdoc
     */
    public function setFullNameAr(string $fullNameAr)
    {
        return $this->setData("full_name_ar", $fullNameAr);
    }
    /**
     * @inheritdoc
     */
    public function getFullNameAr()
    {
        return $this->getData('full_name_ar');
    }
    /**
     * @inheritdoc
     */
    public function getCountryCurrency()
    {
        return $this->getData('country_currency');
    }
    /**
     * @inheritdoc
     */
    public function setCountryCurrency(string $currencyCode)
    {
        return $this->setData("country_currency", $currencyCode);
    }
    /**
     * @inheritdoc
     */
    public function getPushNotificationResponses()
    {
        return $this->getData('push_notification_responses');
    }
    /**
     * @inheritdoc
     */
    public function setPushNotificationResponses(
        array $push_notification_responses
    ) {
        return $this->setData("push_notification_responses", $push_notification_responses);
    }
    /**
     * @inheritdoc
     */
    public function setTwoLetterAbbreviation(string $twoLetterAbbreviation)
    {
        return $this->setData("two_letter_abbreviation", $twoLetterAbbreviation);
    }
    /**
     * @inheritdoc
     */
    public function getTwoLetterAbbreviation()
    {
        return $this->getData('two_letter_abbreviation');
    }
    /**
     * @inheritdoc
     */
    public function setKlevuModels(array $klevuModels)
    {
        return $this->setData("klevu_models", $klevuModels);
    }
    /**
     * @inheritdoc
     */
    public function getKlevuModels()
    {
        return $this->getData('klevu_models');
    }
    /**
     * @inheritdoc
     */
    public function setBaseModels(array $baseModels)
    {
        return $this->setData("base_models", $baseModels);
    }
    /**
     * @inheritdoc
     */
    public function getAdjustModels()
    {
        return $this->getData('adjust_models');
    }
    /**
     * @inheritdoc
     */
    public function getBaseModels()
    {
        return $this->getData('base_models');
    }
    /**
     * @inheritdoc
     */
    public function setAdjustModels(array $adjustModels)
    {
        return $this->setData("adjust_models", $adjustModels);
    }
}
