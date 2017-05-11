<?php

namespace Kobe\Schemas;

use Kobe\Kobe;

class TempDefinition extends Definition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * TempDefinition constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function originToArray()
    {
        return parent::toArray();
    }

    /**
     * @return array
     */
    public function toArrray()
    {
        return Kobe::referenceByClass($this->name)->toArray();
    }
}