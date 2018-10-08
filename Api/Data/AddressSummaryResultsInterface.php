<?php

namespace Zero1\AddressFinder\Api\Data;

interface AddressSummaryResultsInterface
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
     * @return \Zero1\AddressFinder\Api\Data\AddressSummaryInterface[]
     */
    public function getAddresses();

    /**
     * @param \Zero1\AddressFinder\Api\Data\AddressSummaryInterface[] $addresses
     * @return $this
     */
    public function setAddresses($addresses);
}