<?php

namespace Kobe\Contracts;

interface Inheritable
{
    /**
     * @return mixed
     */
    public function getDefault();

    /**
     * @return mixed
     */
    public function getDescription();

    /**
     * @return mixed
     */
    public function getExample();

    /**
     * @return \Kobe\Schemas\Schema[]
     */
    public function getProperties();

    /**
     * @return mixed
     */
    public function getTitle();

    /**
     * @return null|string
     */
    public function getType();

    /**
     * @return null|string
     */
    public function getFormat();

}