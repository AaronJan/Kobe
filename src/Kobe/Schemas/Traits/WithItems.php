<?php

namespace Kobe\Schemas\Traits;

use Kobe\Kobe;
use Kobe\Schemas\Items;
use Kobe\Schemas\Reference;

trait WithItems
{
    /**
     * @var Items|null
     */
    protected $items;

    /**
     * @param \Kobe\Schemas\Items $items
     * @return $this
     */
    public function setItems(Items $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param \Kobe\Schemas\Reference $reference
     * @return $this
     */
    public function setReferenceAsItems(Reference $reference)
    {
        $this->setItems(Kobe::makeItems()->setReference($reference));

        return $this;
    }

    /**
     * @return \Kobe\Schemas\Items|null
     */
    public function getItems()
    {
        return $this->items;
    }

}