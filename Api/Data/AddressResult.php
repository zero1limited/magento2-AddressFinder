<?php
namespace Zero1\AddressFinder\Api\Data;

class AddressResult implements AddressResultInterface
{
    protected $result = false;

    protected $message;

    protected $address;

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
        $this->result = $result;
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
     * @return \Zero1\AddressFinder\Api\Data\AddressInterface
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param \Zero1\AddressFinder\Api\Data\AddressInterface $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
}