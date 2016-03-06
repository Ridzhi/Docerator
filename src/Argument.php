<?php

namespace DocBuilder;


class Argument
{

    const DEFAULT_UNDEFINED = '___undefined___';

    protected $name;
    protected $type;
    protected $default;

    /**
     * @param string $expression expression, looks like '[varType]:varName=[varDefault]',
     * for example 'age', 'int:age', 'int:age=0'
     */
    public function __construct($expression = null)
    {
        if ($expression !== null) {
            list($this->type, $this->name, $this->default) = $this->parse($expression);
        }
    }

    public function getOutput()
    {
        $output = '$' . $this->name;

        if ($this->type) {
            $output = $this->type . ' ' . $output;
        }

        if ($this->default !== self::DEFAULT_UNDEFINED) {
            $output .= ' = ' . $this->default;
        }

        return $output;
    }

    /**
     * @param string $name
     * @return Argument
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $type
     * @return Argument
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $default
     * @return Argument
     */
    public function setDefault($default)
    {
        $this->defineDefault($default);

        return $this;
    }

    public function setDefaultAsString($default)
    {
        $this->defineDefault('\'' . $default . '\'');

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param string $expression
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function parse($expression)
    {
        preg_match('/([\w\|]+(?=\:))?\:?([\w0-9_]+)\=?((?<=\=).*)?/', $expression, $matches);

        $count = count($matches);

        if ($count < 3) {
            throw new \InvalidArgumentException('Not valid expression to parse');
        }

        if ($count === 3) {
            $matches[3] = Argument::DEFAULT_UNDEFINED;
        }

        return array_slice($matches, 1);
    }

    protected function defineDefault($default)
    {
        $this->default = $default;
    }

}