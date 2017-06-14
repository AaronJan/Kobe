<?php

namespace Kobe;

use Kobe\Contracts\Extendable;
use Kobe\Contracts\Inheritable;
use Kobe\Contracts\Schema\Schema as SchemaContract;
use Kobe\Contracts\Schema\Definition as DefinitionContract;

use Kobe\Schemas\Items;
use Kobe\Schemas\Reference;
use Kobe\Schemas\Schema as KobeSchema;
use Kobe\Schemas\TempDefinition;

/**
 * Class Kobe
 *
 * Kobe is a Swagger definition writting tool for PHP, it can be used with framework like Laravel or just native PHP.
 *
 * @license       Apache 2.0
 * @copyright (c) 2017, AaronJan
 * @author        AaronJan <https://github.com/AaronJan/Kobe>
 * @package       Kobe
 */
class Kobe
{
    /**
     * @var array
     */
    static $inheritanceMethodMap = [
        'getProperties'  => 'setProperties',
        'getDefault'     => 'setDefault',
        'getDescription' => 'setDescription',
        'getItems'       => 'setItems',
        'getExample'     => 'setExample',
        'getType'        => 'setType',
        'getFormat'      => 'setFormat',
    ];

    /**
     * @var array
     */
    static $instanceMap = [];

    /**
     * @var TempDefinition[]
     */
    static $tempDefinitions = [];

    /**
     * @param $class
     * @return Object|KobeSchema|mixed
     */
    static public function getInstance($class)
    {
        if (! isset(static::$instanceMap[$class])) {
            static::$instanceMap[$class] = static::makeSchemaInstance($class);
        }

        return static::$instanceMap[$class];
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeSchema()
    {
        return new KobeSchema();
    }

    /**
     * @return \Kobe\Schemas\Items
     */
    static public function makeItems()
    {
        return new Items();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeInteger()
    {
        return (new KobeSchema())
            ->asInteger();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeLong()
    {
        return (new KobeSchema())
            ->asLong();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeFloat()
    {
        return (new KobeSchema())
            ->asFloat();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeDouble()
    {
        return (new KobeSchema())
            ->asDouble();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeString()
    {
        return (new KobeSchema())
            ->asString();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeByte()
    {
        return (new KobeSchema())
            ->asByte();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeBinary()
    {
        return (new KobeSchema())
            ->asBinary();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeBoolean()
    {
        return (new KobeSchema())
            ->asBoolean();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeDate()
    {
        return (new KobeSchema())
            ->asDate();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeDateTime()
    {
        return (new KobeSchema())
            ->asDateTime();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makePassword()
    {
        return (new KobeSchema())
            ->asPassword();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeObject()
    {
        return (new KobeSchema())
            ->asObject();
    }

    /**
     * @return \Kobe\Schemas\Schema
     */
    static public function makeArray()
    {
        return (new KobeSchema())
            ->asArray();
    }

    /**
     * @param \Kobe\Schemas\TempDefinition $definition
     */
    static public function addTempDefinition(TempDefinition $definition)
    {
        static::$tempDefinitions[] = $definition;
    }

    /**
     * @param bool $save
     * @return \Kobe\Schemas\TempDefinition
     */
    static public function makeTempDefinition($save = true)
    {
        $name       = static::makeTempDefinitionName();
        $definition = new TempDefinition($name);

        if ($save) {
            static::addTempDefinition($definition);
        }

        return $definition;
    }

    /**
     * @param      $class
     * @param bool $save
     * @return \Kobe\Schemas\TempDefinition
     */
    static public function makeTempDefinitionFrom($class, $save = true)
    {
        $from = static::getInstance($class);

        $name       = static::makeTempDefinitionName();
        $definition = new TempDefinition($name);

        static::inherit($from, $definition);

        if ($save) {
            static::addTempDefinition($definition);
        }

        return $definition;
    }

    /**
     * @return string
     */
    static public function makeTempDefinitionName()
    {
        return 'temp.' . bin2hex(random_bytes(8));
    }

    /**
     * @param $class
     * @return \Kobe\Schemas\Reference
     */
    static public function referenceByClass($class)
    {
        $instance = Kobe::getInstance($class);

        if (! $instance instanceof DefinitionContract) {
            $message = "[$class] must implement Kobe\\Contracts\\Schema\\Definition.";

            throw new \LogicException($message);
        }

        return new Reference($instance->getName());
    }

    /**
     * @param $name
     * @return \Kobe\Schemas\Reference
     */
    static public function referenceByName($name)
    {
        return new Reference($name);
    }

    /**
     * @param $class
     * @return \Kobe\Contracts\Schema\Schema
     */
    static public function makeSchemaInstance($class)
    {
        $schema = new $class();

        if (! $schema instanceof SchemaContract) {
            $message = "[$class] must implement Kobe\\Contracts\\Schema.";

            throw new \LogicException($message);
        }

        return $schema;
    }

    /**
     * @param string $directory
     * @param string $namespace
     * @param int    $depth
     * @return array
     */
    static public function scanPSR4($directory, $namespace, $depth = 3)
    {
        $classes = Loader::scanPSR4($directory, $namespace, $depth);

        return Kobe::parseDefinitions($classes);
    }

    /**
     * @param array $definitionClasses
     * @param bool  $mergeWithTemp
     * @return array
     */
    static public function parseDefinitions(array $definitionClasses, $mergeWithTemp = true)
    {
        $parsed = [];

        foreach ($definitionClasses as $definitionClass) {
            $definition                     = static::makeDefinitionInstance($definitionClass);
            $parsed[$definition->getName()] = $definition->toArray();
        }

        if ($mergeWithTemp) {
            $parsed = array_merge($parsed, static::parseTempDefinitions());
        }

        return $parsed;
    }

    /**
     * @param $definitionClass
     * @return mixed
     */
    static protected function makeDefinitionInstance($definitionClass)
    {
        $definition = new $definitionClass();

        if (! $definition instanceof DefinitionContract) {
            throw new \LogicException("[$definitionClass] must be an instance of Kobe\\Objects\\Definition");
        }

        return $definition;
    }

    /**
     * @return array
     */
    static public function parseTempDefinitions()
    {
        $parsed = [];

        foreach (static::$tempDefinitions as $name => $definition) {
            $parsed[$definition->getName()] = $definition->originToArray();
        }

        return $parsed;
    }

    /**
     * @param \Kobe\Contracts\Inheritable $from
     * @param \Kobe\Contracts\Extendable  $to
     */
    static public function inherit(Inheritable $from, Extendable $to)
    {
        foreach (static::getInheritanceMethodMap() as $fromMethod => $toMethod) {
            static::inheritIfIsNull([$from, $fromMethod], [$to, $toMethod]);
        }
    }

    /**
     * @return array
     */
    static public function getInheritanceMethodMap()
    {
        return static::$inheritanceMethodMap;
    }

    /**
     * @param callable $from
     * @param callable $to
     */
    static public function inheritIfIsNull(Callable $from, Callable $to)
    {
        $value = call_user_func($from);

        if ($value !== null) {
            call_user_func($to, $value);
        }
    }
}
