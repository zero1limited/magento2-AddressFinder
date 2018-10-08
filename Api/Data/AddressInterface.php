<?php

namespace Zero1\AddressFinder\Api\Data;

interface AddressInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getLine1();

    /**
     * @param string $line
     * @return $this
     */
    public function setLine1($line);

    /**
     * @return string
     */
    public function getLine2();

    /**
     * @param string $line
     * @return $this
     */
    public function setLine2($line);

    /**
     * @return string
     */
    public function getCity();

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * @return string
     */
    public function getCounty();

    /**
     * @param string $county
     * @return $this
     */
    public function setCounty($county);

    /**
     * @return string
     */
    public function getPostcode();

    /**
     * @param string $postcode
     * @return $this
     */
    public function setPostcode($postcode);
}