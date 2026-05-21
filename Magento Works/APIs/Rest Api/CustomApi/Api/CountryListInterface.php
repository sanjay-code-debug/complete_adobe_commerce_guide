<?php
/**
 * Copyright © Codilar Technologies Pvt. Ltd. All rights reserved.
 */
namespace Codilar\CustomApi\Api;

interface CountryListInterface
{
    /**
     * Get country list
     *
     * @return array
     */
    public function getCountryList(): array;
}
