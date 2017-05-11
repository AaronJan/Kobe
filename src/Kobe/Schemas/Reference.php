<?php

namespace Kobe\Schemas;

use Kobe\Contracts\Schema\Schema as SchemaContract;
use Kobe\Contracts\Understandable;

class Reference implements Understandable, SchemaContract
{
    /**
     * @var string
     */
    protected $definitionName;

    /**
     * Reference constructor.
     *
     * @param string $definitionName
     */
    public function __construct($definitionName)
    {

        $this->definitionName = $definitionName;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            '$ref' => "#/definitions/{$this->definitionName}",
        ];
    }

}