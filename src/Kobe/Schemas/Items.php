<?php

namespace Kobe\Schemas;

use Kobe\Contracts\Schema\Schema as SchemaContract;
use Kobe\Contracts\Understandable;
use Kobe\Schemas\Traits;

class Items implements Understandable, SchemaContract
{
    use Traits\WithTypeAndFormat,
        Traits\WithDefault,
        Traits\WithReference,
        Traits\Parse;

    /**
     * @return array
     */
    public function toArray()
    {
        $reference = $this->getReference();

        if (! is_null($reference)) {
            return $reference->toArray();
        }

        return $this->mergeIntoArrayIfNotNull([], [
            'type'    => $this->getType(),
            'format'  => $this->getFormat(),
            'default' => $this->getDefault(),
        ]);
    }

}