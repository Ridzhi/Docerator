<?php

namespace DocBuilder;


class Param
{

    protected $name;
    protected $type;
    protected $default;

    /**
     * @param string $expression
     */
    public function __construct($expression)
    {
        $this->name = '$' . $expression;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

}