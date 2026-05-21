<?php
namespace Codilar\CustomApi\Model\Api;

use Codilar\CustomApi\Api\Data\ProductSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class ProductSearchResults extends SearchResults implements ProductSearchResultsInterface
{
    public const KEY_AVAILABLE_FILTERS = 'available_filters';
    public const KEY_CHILD_CATEGORIES = 'child_categories';

    /**
     * @inheritdoc
     */
    public function getAvailableFilters()
    {
        return $this->_get(self::KEY_AVAILABLE_FILTERS) === null ? [] : $this->_get(self::KEY_AVAILABLE_FILTERS);
    }

    /**
     * @inheritdoc
     */
    public function setAvailableFilters($data)
    {
        return $this->setData(self::KEY_AVAILABLE_FILTERS, $data);
    }

    /**
     * @inheritdoc
     */
    public function getChildCategories()
    {
        return $this->_get(self::KEY_CHILD_CATEGORIES) === null ? [] : $this->_get(self::KEY_CHILD_CATEGORIES);
    }

    /**
     * @inheritdoc
     */
    public function setChildCategories($data)
    {
        return $this->setData(self::KEY_CHILD_CATEGORIES, $data);
    }
}
