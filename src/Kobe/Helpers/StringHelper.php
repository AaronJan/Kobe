<?php

namespace Kobe\Helpers;

/**
 * Class StringHelper
 *
 * @package Kobe\Helpers
 */
class StringHelper
{
    /**
     * @param $haystack
     * @param $needle
     * @return string
     */
    static public function trimWhenStartsWith($haystack, $needle)
    {
        $position = mb_strpos($haystack, $needle);

        if ($position === 0) {
            return mb_substr($haystack, mb_strlen($needle));
        }

        return $haystack;
    }
}