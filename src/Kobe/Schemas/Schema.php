<?php

namespace Kobe\Schemas;

use Kobe\Contracts\Extendable;
use Kobe\Contracts\Inheritable;
use Kobe\Contracts\Schema\Schema as SchemaContract;
use Kobe\Contracts\Understandable;
use Kobe\Schemas\Traits;

class Schema implements Understandable, Extendable, Inheritable, SchemaContract
{
    use Traits\WithExample,
        Traits\WithTitle,
        Traits\WithDefault,
        Traits\WithDescription,
        Traits\WithProperty,
        Traits\WithItems,
        Traits\WithTypeAndFormat,
        Traits\Parse;

    /**
     * @var Understandable[]
     */
    protected $allOf = [];

    /**
     * @param Understandable[] $parents
     * @return $this
     */
    public function setAllOf($parents)
    {
        $this->allOf = $parents;

        return $this;
    }

    /**
     * @return \Kobe\Contracts\Understandable[]
     */
    public function getAllOf()
    {
        return $this->allOf;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $valueMap = [
            'title'       => $this->getTitle(),
            'default'     => $this->getDefault(),
            'description' => $this->getDescription(),
            'type'        => $this->getType(),
            'format'      => $this->getFormat(),
            'example'     => $this->getExample(),
        ];

        $allOf = $this->getAllOf();
        if (! empty($allOf)) {
            $valueMap['allOf'] = $this->translateAll($allOf);
        }

        $items = $this->getItems();
        if (! empty($items)) {
            $valueMap['items'] = $items->toArray();
        }

        $properties = $this->getProperties();
        if (! empty($properties)) {
            $valueMap['properties'] = $this->translateAll($properties);
        }

        $items = $this->getItems();
        if (!empty($items)) {
            $valueMap['items'] = $items->toArray();
        }

        $required = $this->getRequired();
        if (!empty($required)) {
            $valueMap['required'] = $required;
        }

        if ($this->additionalProperties === false) {
            $valueMap['additionalProperties'] = false;
        }

        return $this->mergeIntoArrayIfNotNull([], $valueMap);
    }

    /**
     * @param Understandable[] $understandables
     * @return array
     */
    protected function translateAll(array $understandables)
    {
        $translateds = [];

        foreach ($understandables as $name => $understandable) {
            if (is_integer($name)) {
                $translateds[] = $understandable->toArray();
            } else {
                $translateds[$name] = $understandable->toArray();
            }
        }

        return $translateds;
    }

}
