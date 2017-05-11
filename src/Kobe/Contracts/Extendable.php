<?php

namespace Kobe\Contracts;

interface Extendable
{
    /**
     * @param $example
     * @return $this
     */
    public function setExample($example);

    /**
     * @param $default
     * @return mixed
     */
    public function setDefault($default);

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * @param \Kobe\Schemas\Property[] $propertyArray
     * @return $this
     */
    public function setProperties($propertyArray);

    /**
     * @param array $required
     * @return $this
     */
    public function setRequired(array $required);

    /**
     * @param $example
     * @return $this
     */
    public function setTitle($title);

    /**
     * @param $type
     * @return $this
     */
    public function setType($type);

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format);

}