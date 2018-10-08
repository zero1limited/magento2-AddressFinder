<?php

namespace Zero1\AddressFinder\Api;

interface AddressLookupInterface
{
    /**
     * @param string $cartId
     * @param string $postcode
     * @return \Zero1\AddressFinder\Api\Data\AddressSummaryResultsInterface
     */
    public function getAddresses($cartId, $postcode);

    /**
     * @param string $cartId
     * @param string $addressId
     * @return \Zero1\AddressFinder\Api\Data\AddressResultInterface
     */
    public function getAddress($cartId, $addressId);


}