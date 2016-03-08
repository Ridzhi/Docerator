<?php

namespace DocBuilder;


class DocBlock
{

    const MARK_TAG = '@';
    const MARK_LINE = '*';
    const MARK_BLOCK_BEGIN = '/**';
    const MARK_BLOCK_END = '*/';

    protected $sections = [];

    /**
     * @return DocBlock
     */
    public function api()
    {
        return $this->make('api');
    }

    /**
     * @param string $name
     * @param string $email
     * @return DocBlock
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
     * @param string $description
     * @return DocBlock
     */
    public function category($description = null)
    {
        return $this->make('category', func_get_args());
    }

    /**
     * @param string $description
     * @return DocBlock
     */
    public function copyright($description = null)
    {
        return $this->make('copyright', func_get_args());
    }

    /**
     * @param string $version
     * @param string $description
     * @return DocBlock
     */
    public function deprecated($version = null, $description = null)
    {
        return $this->make('deprecated', func_get_args());
    }

    /**
     * @param string $location
     * @param string $description
     * @param int $startLine
     * @param int $numberOfLines
     * @param bool $inline
     * @return DocBlock
     */
    public function example($location = null, $description = null, $startLine = null, $numberOfLines = null, $inline = false)
    {
        if ($numberOfLines !== null && $startLine === null) {
            throw new \LogicException('If you specify <numberOfLines> you must specify <startLine>');
        }

        $segments = [$location, $startLine, $numberOfLines, $description];

        return $this->make('example', $segments, $inline);
    }

    /**
     * @return DocBlock
     */
    public function filesource()
    {
        return $this->make('filesource');
    }

    /**
     * @param string $description
     * @return DocBlock
     */
    public function ignore($description = null)
    {
        return $this->make('ignore', func_get_args());
    }

    /**
     * @param string $description
     * @param bool $inline
     * @return DocBlock
     */
    public function internal($description = null, $inline = false)
    {
        return $this->make('internal', [$description], $inline, '{%s }}');
    }

    /**
     * @param string $url
     * @param string $name
     * @return DocBlock
     */
    public function license($url = null, $name = null)
    {
        return $this->make('license', func_get_args());
    }

    /**
     * @param string $url
     * @param string $description
     * @param bool $inline
     * @return DocBlock
     */
    public function link($url = null, $description = null, $inline = false)
    {
        return $this->make('link', [$url, $description], $inline);
    }

    /**
     * @param string $name
     * @param array $args Each arg is Argument expression
     * @param string $return
     * @param string $description
     * @return DocBlock
     *
     * @see Argument::__construct()
     */
    public function method($name,  array $args = null,  $return = null, $description = null)
    {
        $method = new Method($name);

        if ($args !== null) {

            foreach ($args as $arg) {
                $method->setArgument(new Argument($arg));
            }

        }

        if ($return !== null) {
            $method->setReturn($return);
        }

        if ($description !== null) {
            $method->setDescription($description);
        }

        return $this->make('method', [$method->getOutput()]);
    }

    /**
     * @param MethodInterface $method
     * @return DocBlock
     */
    public function methodObj(MethodInterface $method)
    {
        return $this->make('method', [$method->getOutput()]);
    }

    /**
     * @param string $name
     * @return DocBlock
     */
    public function package($name = null)
    {
        return $this->make('package', func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return DocBlock
     */
    public function param($name, $type = null, $description = null)
    {
        return $this->variable('param', ...func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return DocBlock
     */
    public function property($name, $type = null, $description = null)
    {
        return $this->variable('property', ...func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return DocBlock
     */
    public function propertyRead($name, $type = null, $description = null)
    {
        return $this->variable('property-read', ...func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return DocBlock
     */
    public function propertyWrite($name, $type = null, $description = null)
    {
        return $this->variable('property-write', ...func_get_args());
    }

    /**
     * @param string|array $type
     * @param string $description
     * @return DocBlock
     */
    public function returnTag($type = null, $description = null)
    {
        $type = $this->parseType($type);

        return $this->make('return', [$type, $description]);
    }

    /**
     * @param string $target URI|FQSEN
     * @param string $description
     * @return DocBlock
     */
    public function see($target = null, $description = null)
    {
        return $this->make('see', func_get_args());
    }

    /**
     * @param string $version
     * @param string $description
     * @return DocBlock
     */
    public function since($version = null, $description = null)
    {
        return $this->make('since', func_get_args());
    }

    /**
     * @param string $description
     * @param int $startLine
     * @param int $numberOfLines
     * @param bool $inline
     * @return DocBlock
     */
    public function source($description = null, $startLine = null, $numberOfLines = null, $inline = false)
    {
        if ($numberOfLines !== null && $startLine === null) {
            throw new \LogicException('If you specify <numberOfLines> you must specify <startLine>');
        }

        $segments = [$startLine, $numberOfLines, $description];

        return $this->make('source', $segments, $inline);
    }

    /**
     * @param string $name
     * @return DocBlock
     */
    public function subpackage($name = null)
    {
        return $this->make('subpackage', func_get_args());
    }

    /**
     * @param string|array $type
     * @param string $description
     * @return DocBlock
     */
    public function throwsTag($type = null, $description = null)
    {
        $type = $this->parseType($type);

        return $this->make('throws', [$type, $description]);
    }

    /**
     * @param string $description
     * @return DocBlock
     */
    public function todo($description = null)
    {
        return $this->make('todo', func_get_args());
    }

    /**
     * @param string $fqsen
     * @param String $description
     * @return DocBlock
     */
    public function uses($fqsen = null, $description = null)
    {
        return $this->make('uses', func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return DocBlock
     */
    public function varTag($name, $type = null, $description = null)
    {
        return $this->variable('var', ...func_get_args());
    }

    /**
     * @param string $version
     * @param string $description
     * @return DocBlock
     */
    public function version($version = null, $description = null)
    {
        return $this->make('version', func_get_args());
    }

    /**
     * @param string $value
     * @return DocBlock
     */
    public function text($value)
    {
        return $this->make(null, func_get_args());
    }

    /**
     * @param int $count
     * @return DocBlock
     */
    public function emptyLine($count = 1)
    {
        $sequence = $this->getLineBreakSequence($count);
        //n line breaks === n+1 empty lines
        $section = substr($sequence, 0, -1);
        $segments = [$section];

        return $this->make(null, $segments);
    }

    public function getOutput()
    {
        $sections = $this->processSections();
        array_unshift($sections, self::MARK_BLOCK_BEGIN);
        array_push($sections, ' ' . self::MARK_BLOCK_END);

        return implode("\n", $sections);
    }

    function __toString()
    {
        return $this->getOutput();
    }

    /**
     * @param string $tag
     * @param array $segments
     * @param bool $inline
     * @param string $format
     * @return DocBlock
     */
    protected function make($tag = null, array $segments = [], $inline = false, $format = '{%s}')
    {
        if ($tag !== null) {
            array_unshift($segments, self::MARK_TAG . $tag);
        }

        $section = $this->union($segments);

        if ($inline === true) {
            $section = sprintf($format, $section);
        }

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

    /**
     * @return array
     */
    protected function processSections()
    {
        return array_map(function (& $section) {
            $section = $this->processSection($section);
        }, $this->sections);
    }

    /**
     * @paran string $tag
     * @param $tag
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return DocBlock
     */
    protected function variable($tag, $name, $type = null, $description = null)
    {
        $type = $this->parseType($type);

        return $this->make($tag, [$type, '$' . $name, $description]);
    }

    /**
     * @param string|array $type
     * @return string
     */
    protected function parseType($type)
    {
        if (is_array($type)) {
            $type = implode('|', $type);
        }

        return $type;
    }

    protected function processSection($section)
    {
        $lines = explode("\n", $section);

        array_walk($lines, function (& $line) {
            $line = $this->formatToDocLine($line);
        });

        return implode("\n", $lines);
    }

    protected function formatToDocLine($input)
    {
        return ' ' . self::MARK_LINE . ' ' . trim($input);
    }

} 