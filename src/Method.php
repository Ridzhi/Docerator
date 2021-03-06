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
        $this->return = self::parseType($return);

        return $this;
    }

    /**
     * @param Argument $arg
     * @return $this
     */
    public function setArgument(Argument $arg)
    {
        $this->args[] = $arg;

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

            $segments = [];
            /**
             * @var Argument $arg
             */
            foreach ($this->args as $arg) {
                $segments[] = $arg->getOutput();
            }

            $body = implode(', ', $segments);
        }

        return '(' . $body . ')';
    }

    protected static function parseType($type)
    {
        if (is_string($type)) {
            return $type;
        }

        if (is_array($type)) {
            return implode('|', $type);
        }

        throw new \InvalidArgumentException('Type param can be array|string only');
    }

} 