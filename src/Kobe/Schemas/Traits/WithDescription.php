<?php

namespace Kobe\Schemas\Traits;

trait WithDescription
{
    /**
     * @var mixed|null
     */
    protected $description;

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

}