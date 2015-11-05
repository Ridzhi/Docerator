<?php

namespace DocBuilder;


class Method
{

    protected $name;
    protected $return;
    protected $args = [];
    protected $description;

    /**
     * @param string $name
     */
    function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string|array $return
     * @return $this
     */
    public function setReturn($return)
    {
        $this->return = $this->parseReturn($return);

        return $this;
    }

    /**
     * @param string $name
     * @param string|null $type
     * @return $this
     */
    public function setArgument($name, $type = null)
    {
        $argument = '$' . $name;

        if ($type !== null) {
            $argument = $type . ' ' . $argument;
        }

        $this->args[] = $argument;

        return $this;
    }

    public function getOutput()
    {
        $segments = [$this->return, $this->name . $this->getSignature(), $this->description];

        return implode(' ', array_filter($segments));
    }

    protected function getSignature()
    {
        $body = '';

        if (!empty($this->args)) {
            $body = implode(', ', $this->args);
        }

        return '(' . $body . ')';
    }

    protected function parseReturn($return)
    {
        if ($return === null) {
            return 'void';
        }

        if (is_string($return)) {
            return $return;
        }

        if (is_array($return)) {
            return implode('|', $return);
        }

        throw new \InvalidArgumentException('Argument $return must have array|string|null type');
    }

} 