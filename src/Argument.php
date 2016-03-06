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
            list($this->type, $this->name, $this->default) = self::parse($expression);
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

    /**
     * Set default with quotes
     *
     * @param string $default
     * @return Argument
     */
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

    protected function defineDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @param string $expression
     * @return array
     * @throws \InvalidArgumentException
     */
    protected static function parse($expression)
    {
        // three capturing groups [type] name [default], [] - not required
        $hasErrors = (false === preg_match('/(\w+(?=\:))?\:?([\w0-9_]+)\=?((?<=\=).*)?/', $expression, $matches));

        if($hasErrors) {
            throw new \InvalidArgumentException('preg_match error, may be invalid expression to parse');
        }

        $result = array_slice($matches, 1);

        // because required(without ?) capturing group is a second
        $minMatchesIfValid = 2;

        if (count($result) < $minMatchesIfValid) {
            throw new \InvalidArgumentException('Invalid expression to parse');
        }

        // default capturing group is a third and it not required
        $isDefaultUndefined = !(isset($result[2]));

        if ($isDefaultUndefined) {
            $result[2] = Argument::DEFAULT_UNDEFINED;
        }

        return $result;
    }

}