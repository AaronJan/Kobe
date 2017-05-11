<?php

namespace Kobe\Schemas\Traits;

trait Parse
{
    /**
     * @param array $array
     * @param       $key
     * @param       $value
     */
    protected function setArrayIfNotNull(array &$array, $key, $value)
    {
        if (! is_null($value)) {
            $array[$key] = $value;
        }
    }

    /**
     * @param array $array
     * @param array $valueMap
     * @return array
     */
    protected function mergeIntoArrayIfNotNull(array $array, array $valueMap)
    {
        $pairs = array_map(function ($value, $key) {
            return [$key, $value];
        }, $valueMap, array_keys($valueMap));

        $mergeWith = array_reduce($pairs, function ($carry, $pair) {
            list($key, $value) = $pair;

            if ($value !== null) {
                $carry[$key] = $value;
            }

            return $carry;
        }, []);

        return array_merge($array, $mergeWith);
    }

}