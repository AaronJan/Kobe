<?php

namespace Kobe\Schemas;

use Kobe\Contracts\Schema\Definition as DefinitionContract;
use Kobe\Constant;
use Kobe\Helpers\StringHelper;
use Kobe\Kobe;

abstract class Definition extends Schema implements DefinitionContract
{
    /**
     * @var string
     */
    protected $type = Constant::TYPE_OBJECT;

    /**
     * @return string
     */
    public function getName()
    {
        $trimmed = StringHelper::trimWhenStartsWith(
            get_class($this),
            $this->getBaseNamespace()
        );

        return str_replace('\\', '.', $trimmed);
    }

    /**
     * @return string
     */
    public function getBaseNamespace()
    {
        // Example:
        // return "App\\Swagger\\Definitions\\";
        //
        return '';
    }

    /**
     * @return \Kobe\Schemas\Reference
     */
    public function getReference()
    {
        return Kobe::referenceByName($this->getName());
    }
}