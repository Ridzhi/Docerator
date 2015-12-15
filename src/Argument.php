<?php

namespace DocBuilder;


class Argument
{

    const DEFAULT_NOT_ENABLED = '___null___';

    protected $name;
    protected $type;
    protected $default;

    /**
     * @param string $expression
     */
    public function __construct($expression = null)
    {
        if ($expression !== null) {
            $info = $this->parse($expression);
            $this->name = $info['name'];
            $this->type = $info['type'];
            $this->default = $info['default'];
        }
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
            $matches[3] = Argument::DEFAULT_NOT_ENABLED;
        }

        return [
            'type' => $matches[1],
            'name' => $matches[2],
            'default' => $matches[3]
        ];

    }

}