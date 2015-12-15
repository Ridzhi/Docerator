<?php

namespace DocBuilder;

class Method implements MethodInterface
{

    protected $name;
    protected $return;
    protected $args = [];
    protected $description;

    /**
     * @param string $name
     */
    function __construct($name = null)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string|array $return
     * @return self
     */
    public function setReturn($return)
    {
        $this->return = self::parseReturn($return);

        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @return self
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

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getOutput()
    {
        if (!$this->name) {
            throw new \RuntimeException('Method name is required');
        }

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

    protected static function parseReturn($return)
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