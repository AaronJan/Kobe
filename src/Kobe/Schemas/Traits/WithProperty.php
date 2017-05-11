<?php

namespace Kobe\Schemas\Traits;

use Kobe\Contracts\Understandable;

trait WithProperty
{
    /**
     * @var \Kobe\Contracts\Understandable[]
     */
    protected $properties = [];

    /**
     * @var string[]
     */
    protected $required = [];

    /**
     * @param string                         $name
     * @param \Kobe\Contracts\Understandable $value
     * @return $this
     */
    public function setProperty($name, Understandable $value)
    {
        $this->properties[$name] = $value;

        return $this;
    }

    /**
     * @param \Kobe\Contracts\Understandable[] $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        foreach ($properties as $name => $value) {
            $this->setProperty($name, $value);
        }

        return $this;
    }

    /**
     * @return \Kobe\Contracts\Understandable[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string|string[] $required
     * @return $this
     */
    public function addRequired($required)
    {
        if (! is_array($required)) {
            $required = [$required];
        }

        $this->required = array_unique(
            array_merge($this->required, $required)
        );

        return $this;
    }

    /**
     * @param string[] $required
     * @return $this
     */
    public function setRequired(array $required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRequired()
    {
        return $this->required;
    }

}