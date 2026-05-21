<?php
/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */
namespace Codilar\CustomApi\Model;

use Codilar\CustomApi\Api\CityRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use MagePsycho\RegionCityPro\Api\CityRepositoryInterface as CoreCityRepositoryInterface;
use MagePsycho\RegionCityPro\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;

class CityRepository implements CityRepositoryInterface
{
    /**
     * @var CoreCityRepositoryInterface
     */
    protected $cityRepository;

    /**
     * @var CityCollectionFactory
     */
    protected $cityCollection;

    /**
     * @param CoreCityRepositoryInterface $cityRepository
     * @param CityCollectionFactory $cityCollection
     */
    public function __construct(
        CoreCityRepositoryInterface  $cityRepository,
        CityCollectionFactory $cityCollection
    ) {
        $this->cityRepository = $cityRepository;
        $this->cityCollection = $cityCollection;
    }

    /**
     * @inheritdoc
     */
    public function getCities(SearchCriteriaInterface $searchCriteria)
    {
        foreach ($searchCriteria->getFilterGroups() as $filters) {
            foreach ($filters->getFilters() as $filter) {
                if ($filter->getField() === "country_id") {
                    $cities = $this->cityCollection->create()->addCountryFilter([$filter->getValue()])->getData();
                    return $cities;
                }
            }
        }
    }
}
