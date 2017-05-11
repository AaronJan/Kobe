<?php

namespace Kobe\Schemas\Traits;

use Kobe\Constant;

trait WithTypeAndFormat
{
    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $format;

    //
    // Types and Formats
    //

    /**
     * @return $this
     */
    public function asInteger()
    {
        return $this
            ->setType(Constant::TYPE_INTEGER)
            ->setFormat(Constant::FORMAT_INT32);
    }

    /**
     * @return $this
     */
    public function asLong()
    {
        return $this
            ->setType(Constant::TYPE_INTEGER)
            ->setFormat(Constant::FORMAT_INT32);
    }

    /**
     * @return $this
     */
    public function asFloat()
    {
        return $this
            ->setType(Constant::TYPE_INTEGER)
            ->setFormat(Constant::FORMAT_INT32);
    }

    /**
     * @return $this
     */
    public function asDouble()
    {
        return $this
            ->setType(Constant::TYPE_INTEGER)
            ->setFormat(Constant::FORMAT_INT32);
    }

    /**
     * @return $this
     */
    public function asString()
    {
        return $this
            ->setType(Constant::TYPE_STRING);
    }

    /**
     * @return $this
     */
    public function asByte()
    {
        return $this
            ->setType(Constant::TYPE_STRING)
            ->setFormat(Constant::FORMAT_BYTE);
    }

    /**
     * @return $this
     */
    public function asBinary()
    {
        return $this
            ->setType(Constant::TYPE_STRING)
            ->setFormat(Constant::FORMAT_BINARY);
    }

    /**
     * @return $this
     */
    public function asBoolean()
    {
        return $this
            ->setType(Constant::TYPE_BOOLEAN);
    }

    /**
     * @return $this
     */
    public function asDate()
    {
        return $this
            ->setType(Constant::TYPE_STRING)
            ->setFormat(Constant::FORMAT_DATE);
    }

    /**
     * @return $this
     */
    public function asDateTime()
    {
        return $this
            ->setType(Constant::TYPE_STRING)
            ->setFormat(Constant::FORMAT_DATETIME);
    }

    /**
     * @return $this
     */
    public function asPassword()
    {
        return $this
            ->setType(Constant::TYPE_STRING)
            ->setFormat(Constant::FORMAT_PASSWORD);
    }

    /**
     * @return $this
     */
    public function asObject()
    {
        return $this
            ->setType(Constant::TYPE_OBJECT);
    }

    /**
     * @return $this
     */
    public function asArray()
    {
        return $this
            ->setType(Constant::TYPE_ARRAY);
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }
}