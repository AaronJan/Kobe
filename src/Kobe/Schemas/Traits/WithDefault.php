<?php

namespace Kobe\Schemas\Traits;

trait WithDefault
{
    /**
     * @var mixed|null
     */
    protected $default;

    /**
     * @param $default
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

}