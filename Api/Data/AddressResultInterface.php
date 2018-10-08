<?php

namespace Zero1\AddressFinder\Api\Data;

interface AddressResultInterface
{
    /**
     * @return bool
     */
    public function getResult();

    /**
     * @param bool $result
     * @return $this
     */
    public function setResult($result);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * @return \Zero1\AddressFinder\Api\Data\AddressInterface
     */
    public function getAddress();

    /**
     * @param \Zero1\AddressFinder\Api\Data\AddressInterface $address
     * @return $this
     */
    public function setAddress($address);
}