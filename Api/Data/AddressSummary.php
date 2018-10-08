<?php
namespace Zero1\AddressFinder\Api\Data;

class AddressSummary implements AddressSummaryInterface
{
    protected $id;

    protected $description;

    public function __construct(
        $id = null,
        $description = null
    ){
        $this->setId($id)
            ->setDescription($description);
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}