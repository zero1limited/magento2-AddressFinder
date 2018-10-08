<?php
namespace Zero1\AddressFinder\Api\Data;

class AddressSummaryResults implements AddressSummaryResultsInterface
{
    protected $result = false;

    protected $message;

    protected $addresses = [];

    /**
     * @return bool
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param bool $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = (bool)$result;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return \Zero1\AddressFinder\Api\Data\AddressSummaryInterface[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param \Zero1\AddressFinder\Api\Data\AddressSummaryInterface[] $addresses
     * @return $this
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }
}