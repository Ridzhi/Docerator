<?php

namespace DocBuilder;

class DocBuilder
{

    const MARK_TAG = '@';
    const MARK_DOCLINE = '*';
    const MARK_DOCBLOCK_BEGIN = '/**';
    const MARK_DOCBLOCK_END = '*/';

    protected $sections = [];

    function __construct()
    {

    }

    function __toString()
    {
        return $this->build();
    }

    public function api()
    {
        return $this->make('api');
    }

    /**
     * @param null $name
     * @param null $email
     * @return $this
     */
    public function author($name = null, $email = null)
    {
        $args = func_get_args();

        if (isset($args[1])) {
            $args[1] = '<' . $email . '>';
        }

        return $this->make('author', $args);
    }

    /**
     * @param null $description
     * @return $this
     */
    public function category($description = null)
    {
        return $this->make('category', func_get_args());
    }

    /**
     * @param null $description
     * @return $this
     */
    public function copyright($description = null)
    {
        return $this->make('copyright', func_get_args());
    }

    public function deprecated($version = null, $description = null)
    {
        return $this->make('deprecated', func_get_args());
    }

    public function example($location = null, $description = null, $startLine = null, $numberOfLines = null)
    {
        if ($numberOfLines !== null && $startLine === null) {
            throw new \LogicException('If you specify <numberOfLines> you must specify <startLine>');
        }

        $segments = [$location, $startLine, $numberOfLines, $description];

        return $this->make('example', $segments);
    }

    public function filesource()
    {
        return $this->make('filesource', func_get_args());
    }

    public function ignore($description = null)
    {
        return $this->make('ignore', func_get_args());
    }

    public function internal($description = null, $inline = false)
    {
        $args = func_get_args();
        array_unshift($args, '@internal');

        if ($inline === true) {

        }

        return $this->make(null, func_get_args());
    }

    public function license($url = null, $name = null)
    {
        return $this->make('license', func_get_args());
    }

    public function link($url = null, $description = null)
    {
        return $this->make('link', func_get_args());
    }

    /**
     * @param string $name
     * @param callable $callback
     * @return $this
     */
    public function method($name, $callback = null)
    {
        $method = new Method($name);

        if ($callback !== null) {
            call_user_func($callback, $method);
        }

        return $this->make('method', [$method->getOutput()]);
    }

    public function param($name, $type = null, $description = null)
    {

    }

    /**
     * @param $value
     * @return $this
     */
    public function text($value)
    {
        return $this->make(null, func_get_args());
    }

    /**
     * @param int $count
     * @return $this
     */
    public function emptyLine($count = 1)
    {
        $sequence = $this->getLineBreakSequence($count);
        //n line breaks === n+1 empty lines
        $section = substr($sequence, 0, -1);
        $segments = [$section];
        return $this->make(null, $segments);
    }

    protected function make($tag = null, array $segments = [])
    {
        if ($tag !== null) {
            array_unshift($segments, self::MARK_TAG . $tag);
        }

        $section = $this->union($segments);
        $this->push($section);

        return $this;
    }

    protected function union(array $segments = [])
    {
        $segments = array_filter($segments);
        return implode(' ', $segments);
    }

    protected function push($section)
    {
        array_push($this->sections, $section);
    }

    protected function getLineBreakSequence($count)
    {
        if ($count <= 0) {
            throw new \LogicException('LineBreak must be more than zero');
        }

        return ($count === 1) ? "\n" : implode(array_fill(0, $count, "\n"));
    }

    protected function convertToDocLine($input)
    {
        return self::MARK_DOCLINE . ' ' . $input;
    }

    protected function prepareSections()
    {
        array_walk($this->sections, function (& $piece) {
            $piece = $this->processSection($piece);
        });
    }

    protected function processSection($section)
    {
        $lines = explode("\n", $section);

        array_walk($lines, function (& $line) {
            $line = $this->convertToDocLine(trim($line));
        });

        return implode("\n", $lines);
    }

    protected function build()
    {
//        array_unshift($this->doc, self::BEGIN_MARK);
//        array_push($this->doc, self::END_MARK);
        $this->prepareSections();
        return implode("\n", $this->sections);
    }

    /**
     * @param array|null $args
     * @return string
     */
    protected function getSignature($args)
    {
        $body = '';

        if ($args !== null) {

            $vars = [];

            foreach ($args as $definition) {
                $vars[] = implode(' ', $definition);
            }

            $body = implode(', ', $vars);
        }

        return '(' . $body . ')';
    }

//    protected function isValidMethodArgs($args)
//    {
//        if(!is_array($args)) {
//            throw new \InvalidArgumentException('Method argument $args must be array');
//        }
//
//        foreach ($args as $definition) {
//            if($f) {
//
//            }
//        }
//
//
//    }


} 