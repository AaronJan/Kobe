<?php

namespace Kobe;

use Kobe\Helpers\StringHelper;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class Loader
 *
 * @package Kobe
 */
class Loader
{
    /**
     * @param        $directory
     * @param string $namespace
     * @param int    $depth
     * @return array
     */
    static public function scanPSR4($directory, $namespace, $depth = 3)
    {
        $directory = realpath($directory);

        $filePaths = iterator_to_array(
            Finder::create()->files()->depth("< $depth")->ignoreDotFiles(true)->in($directory),
            false
        );

        $classes = array_map(function (SplFileInfo $filePath) use ($directory, $namespace) {
            $class = trim(
                StringHelper::trimWhenStartsWith($filePath->getRealPath(), $directory),
                DIRECTORY_SEPARATOR
            );
            $class = str_replace(DIRECTORY_SEPARATOR, '\\', $class);

            return $namespace . static::trimFileSuffix($class);
        }, $filePaths);

        return $classes;
    }

    /**
     * @param $name
     * @return mixed
     */
    static public function trimFileSuffix($name)
    {
        return preg_replace('/\.php5?$/', '', $name);
    }
}
