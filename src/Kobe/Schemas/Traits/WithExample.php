<?php

namespace Kobe\Schemas\Traits;

trait WithExample
{
    /**
     * @var mixed|null
     */
    protected $example;

    /**
     * @param $example
     * @return $this
     */
    public function setExample($example)
    {
        $this->example = $example;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExample()
    {
        return $this->example;
    }

}