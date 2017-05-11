<?php

namespace Kobe\Schemas\Traits;

use Kobe\Schemas\Reference;

trait WithReference
{
    /**
     * @var Reference|null
     */
    protected $reference;

    /**
     * @param \Kobe\Schemas\Reference $reference
     * @return $this
     */
    public function setReference(Reference $reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return \Kobe\Schemas\Reference|null
     */
    public function getReference()
    {
        return $this->reference;
    }

}