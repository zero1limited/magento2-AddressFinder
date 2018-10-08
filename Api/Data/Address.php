<?php
namespace Zero1\AddressFinder\Api\Data;

class Address implements AddressInterface
{
    protected $id;

    protected $line1;

    protected $line2;

    protected $city;

    protected $county;

    protected $postcode;

    public function __construct(
        $id = null,
        $line1 = null,
        $line2 = null,
        $city = null,
        $county = null,
        $postcode = null
    ){
        $this->setId($id)
            ->setLine1($line1)
            ->setLine2($line2)
            ->setCity($city)
            ->setCounty($county)
            ->setPostcode($postcode);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * @param string $line
     * @return $this
     */
    public function setLine1($line)
    {
        $this->line1 = $line;
        return $this;
    }

    /**
     * @return string
     */
    public function getLine2()
    {
        return $this->line2;
    }

    /**
     * @param string $line
     * @return $this
     */
    public function setLine2($line)
    {
        $this->line2 = $line;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param string $county
     * @return $this
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     * @return $this
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }
}