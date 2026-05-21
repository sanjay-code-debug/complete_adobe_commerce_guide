<?php
namespace Codilar\CustomApi\Model\Api;

use Codilar\CustomApi\Api\Data\AvailableFilterInterface;
use Magento\Framework\DataObject;
use Magento\Swatches\Helper\Data;
use Magento\Swatches\Helper\Media;

class AvailableFilter extends DataObject implements AvailableFilterInterface
{
    /**
     * @var Data
     */
    protected $swatchHelper;

    protected $mediaHelper;

    /**
     * @param Data $swatchHelper
     * @param Media $mediaHelper
     * @param array $data
     */
    public function __construct(
        Data $swatchHelper,
        Media $mediaHelper,
        array $data = []
    ) {
        parent::__construct($data);
        $this->swatchHelper = $swatchHelper;
        $this->mediaHelper  = $mediaHelper;
    }

    /**
     * @param $filter
     *
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->setData('filter', $filter);
        $this->getAttributeCode();
        $this->getName();
        $this->getOptions();
        $this->getAttributeType();
        return $this->setData('filter', $filter);
    }

    /**
     * @return string|null
     */
    private function getAttribute()
    {
        if ($this->hasData('filter')) {
            return $this->getData('filter')->hasAttributeModel() ?
                $this->getData('filter')->getAttributeModel() :
                null;
        }
        return null;
    }
    /**
     * @inheritdoc
     */
    public function getName()
    {
        if (!$this->hasData('name') && $this->hasData('filter')) {
            $this->setData('name', $this->getData('filter')->getName());
        }
        return $this->getData('name');
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        if (!$this->hasData('options') && $this->hasData('filter')) {
            $swatches = null;
            $options = [];
            $attribute = $this->getAttribute();
            if ($attribute) {
                $additionalData = $attribute->getAdditionalData();
                if (strpos($additionalData, 'swatch_input_type') !== false) {
                    $optionIds = [];
                    foreach ($this->getData('filter')->getItems() as $item) {
                        $optionIds[] = $item->getValue();
                    }
                    $swatches = $this->swatchHelper->getSwatchesByOptionsId($optionIds);
                }
            }
            if ($this->getAttributeCode() == 'price') {
                $options = [[
                    'min' => $this->getData('filter')->getMin(),
                    'max' => $this->getData('filter')->getMax()
                ]];
            } else {
                foreach ($this->getData('filter')->getItems() as $item) {
                    $option = [
                        'label' => $item->getLabel(),
                        'value' => $item->getValue()
                    ];
                    if ($swatches) {
                        if (isset($swatches[$item->getValue()]['value'])) {
                            $imageValue = $this->mediaHelper->getSwatchAttributeImage('swatch_thumb', $swatches[$item->getValue()]['value']);
                            if ($imageValue) {
                                $url = $this->mediaHelper->getSwatchMediaUrl().$swatches[$item->getValue()]['value'];
                                $option['swatch_value'] = $url;
                            } else {
                                $option['swatch_value'] = $swatches[$item->getValue()]['value'];
                            }
                        }
                    }

                    $options[] = $option;
                }
            }
            $this->setData('options', $options);
        }
        return $this->getData('options');
    }

    /**
     * @inheritdoc
     */
    public function getAttributeCode()
    {
        if (!$this->hasData('attribute_code') && $this->hasData('filter')) {
            $attribute = $this->getAttribute();
            $this->setData('attribute_code', $attribute ? $attribute->getAttributeCode() : 'category_ids');
        }
        return $this->getData('attribute_code');
    }

    /**
     * @inheritdoc
     */
    public function getAttributeType()
    {
        if (!$this->hasData('attribute_type') && $this->hasData('filter')) {
            $attribute = $this->getAttribute();
            $type = 'checkbox';
            if ($attribute) {

                $additionalData =  $attribute->getAdditionalData();
                if (strpos($additionalData, 'swatch_input_type') !== false) {
                    $type = 'swatch';
                }
                if ($attribute->getBackendType() == 'decimal') {
                    $type = 'range';
                }
            }
            $this->setData('attribute_type', $type);
        }
        return $this->getData('attribute_type');
    }

}
