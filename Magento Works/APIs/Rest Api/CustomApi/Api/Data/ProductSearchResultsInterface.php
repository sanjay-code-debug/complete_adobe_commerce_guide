<?php
namespace Codilar\CustomApi\Api\Data;

interface ProductSearchResultsInterface extends \Magento\Catalog\Api\Data\ProductSearchResultsInterface
{

    /**
     * Retrieve available filter
     *
     * @return \Codilar\CustomApi\Api\Data\AvailableFilterInterface[]
     */
    public function getAvailableFilters();

    /**
     * @param \Codilar\CustomApi\Api\Data\AvailableFilterInterface[] $data
     *
     * @return $this
     */
    public function setAvailableFilters($data);

    /**
     * Retrieve sub categories
     *
     * @return \Magento\Framework\DataObject[]|null
     */
    public function getChildCategories();

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setChildCategories($data);
}
