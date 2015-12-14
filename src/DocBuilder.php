<?php

namespace DocBuilder;

class DocBuilder
{

    const MARK_TAG = '@';
    const MARK_LINE = '*';
    const MARK_BLOCK_BEGIN = '/**';
    const MARK_BLOCK_END = '*/';

    protected $sections = [];


    public function getOutput()
    {
        $this->prepareSections();
        array_unshift($this->sections, self::MARK_BLOCK_BEGIN);
        array_push($this->sections, ' ' . self::MARK_BLOCK_END);
        return implode("\n", $this->sections);
    }

    function __toString()
    {
        return $this->getOutput();
    }

    public function api()
    {
        return $this->make('api');
    }

    /**
     * @param string $name
     * @param string $email
     * @return self
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
     * @return self
     */
    public function category($description = null)
    {
        return $this->make('category', func_get_args());
    }

    /**
     * @param string $description
     * @return self
     */
    public function copyright($description = null)
    {
        return $this->make('copyright', func_get_args());
    }

    /**
     * @param string $version
     * @param string $description
     * @return self
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
     * @return self
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
     * @return self
     */
    public function filesource()
    {
        return $this->make('filesource');
    }

    /**
     * @param string $description
     * @return self
     */
    public function ignore($description = null)
    {
        return $this->make('ignore', func_get_args());
    }

    /**
     * @param string $description
     * @param bool $inline
     * @return self
     */
    public function internal($description = null, $inline = false)
    {
        return $this->make('internal', [$description], $inline, '{%s }}');
    }

    public function license($url = null, $name = null)
    {
        return $this->make('license', func_get_args());
    }

    /**
     * @param string $url
     * @param string $description
     * @param bool $inline
     * @return self
     */
    public function link($url = null, $description = null, $inline = false)
    {
        return $this->make('link', [$url, $description], $inline);
    }

    /**
     * @param string $name
     * @param string $return
     * @param string $description
     * @param array $args Each arg is string literal that looks like as 'argName' or 'argType argName'
     * @return self
     */
    public function method($name, $return = null, $description = null, array $args = null)
    {
        $method = new Method($name);

        if ($return !== null) {
            $method->setReturn($return);
        }

        if ($description !== null) {
            $method->setDescription($description);
        }

        if ($args !== null) {

            foreach ($args as $arg) {
                $method->setArgument(...self::parseMethodArgument($arg));
            }

        }

        return $this->make('method', [$method->getOutput()]);
    }

    /**
     * @param MethodInterface $method
     * @return self
     */
    public function methodObj(MethodInterface $method)
    {
        return $this->make('method', [$method->getOutput()]);
    }

    /**
     * @param string $name
     * @return self
     */
    public function package($name = null)
    {
        return $this->make('package', func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return self
     */
    public function param($name, $type = null, $description = null)
    {
        return $this->variable('param', ...func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return self
     */
    public function property($name, $type = null, $description = null)
    {
        return $this->variable('property', ...func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return self
     */
    public function propertyRead($name, $type = null, $description = null)
    {
        return $this->variable('property-read', ...func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return self
     */
    public function propertyWrite($name, $type = null, $description = null)
    {
        return $this->variable('property-write', ...func_get_args());
    }

    /**
     * @param string|array $type
     * @param string $description
     * @return self
     */
    public function returnTag($type = null, $description = null)
    {
        $type = $this->parseType($type);

        return $this->make('return', [$type, $description]);
    }

    /**
     * @param string $target URI|FQSEN
     * @param string $description
     * @return self
     */
    public function see($target = null, $description = null)
    {
        return $this->make('see', func_get_args());
    }

    /**
     * @param string $version
     * @param string $description
     * @return self
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
     * @return self
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
     * @return self
     */
    public function subpackage($name = null)
    {
        return $this->make('subpackage', func_get_args());
    }

    /**
     * @param string|array $type
     * @param string $description
     * @return self
     */
    public function throwsTag($type = null, $description = null)
    {
        $type = $this->parseType($type);

        return $this->make('throws', [$type, $description]);
    }

    /**
     * @param string $description
     * @return self
     */
    public function todo($description = null)
    {
        return $this->make('todo', func_get_args());
    }

    /**
     * @param string $fqsen
     * @param String $description
     * @return self
     */
    public function uses($fqsen = null, $description = null)
    {
        return $this->make('uses', func_get_args());
    }

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return self
     */
    public function varTag($name, $type = null, $description = null)
    {
        return $this->variable('var', ...func_get_args());
    }

    /**
     * @param string $version
     * @param string $description
     * @return self
     */
    public function version($version = null, $description = null)
    {
        return $this->make('version', func_get_args());
    }

    /**
     * @param string $value
     * @return self
     */
    public function text($value)
    {
        return $this->make(null, func_get_args());
    }

    /**
     * @param int $count
     * @return self
     */
    public function emptyLine($count = 1)
    {
        $sequence = $this->getLineBreakSequence($count);
        //n line breaks === n+1 empty lines
        $section = substr($sequence, 0, -1);
        $segments = [$section];
        return $this->make(null, $segments);
    }

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

    protected function convertToDocLine($input)
    {
        return ' ' . self::MARK_LINE . ' ' . $input;
    }

    protected function prepareSections()
    {
        array_walk($this->sections, function (& $section) {
            $section = $this->processSection($section);
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

    /**
     * @param string $name
     * @param string|array $type
     * @param string $description
     * @return DocBuilder
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

    protected static function parseMethodArgument($arg)
    {
        preg_match_all('/([\w0-9\[\]\|]+)/', $arg, $matches);
        $segments = $matches[0];

        $count = count($segments);

        if ($count > 2 || $count === 0) {
            throw new \InvalidArgumentException(sprintf('"%s" isn\'t correct arg. Look doc', $arg));
        }

        if ($count === 2) {
            list($type, $name) = $segments;
        } else {
            $name = $segments[0];
            $type = null;
        }

        return [$name, $type];
    }

} 