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
     * @param string $description
     * @param array|string $return
     * @throws \InvalidArgumentException
     */
    function __construct($name, $description = null, $return = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->return = $this->parseReturn($return);
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setReturn($return)
    {
        $this->return = $this->parseReturn($return);
    }

    public function setArgument($name, $type = null)
    {
        $argument = '$' . $name;

        if ($type !== null) {
            $argument = $type . ' ' . $argument;
        }

        $this->args[] = $argument;
    }


    public function getOutput()
    {
        $segments = [$this->return, $this->name . $this->getSignature(), $this->description];
        return implode(' ', $segments);
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