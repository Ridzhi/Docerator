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
    public function tagApi()
    {
        return $this->make('api');
    }

    /**
     * @param string $name
     * @param string $email
     * @return DocBlock
     */
    public function tagAuthor($name = null, $email = null)
    {
        if ($email !== null) {
            $email = '<' . $email . '>';
        }

        return $this->make('author', [$name, $email]);
    }

    /**
     * @param string $description
     * @return DocBlock
     */
    public function tagCategory($description = null)
    {
        return $this->make('category', [$description]);
    }

    /**
     * @param string $description
     * @return DocBlock
     */
    public function tagCopyright($description = null)
    {
        return $this->make('copyright', [$description]);
    }

    /**
     * @param string $version
     * @param string $description
     * @return DocBlock
     */
    public function tagDeprecated($version = null, $description = null)
    {
        return $this->make('deprecated', [$version, $description]);
    }

    /**
     * @param string $location
     * @param string $description
     * @param int $startLine
     * @param int $numberOfLines
     * @param bool $inline
     * @return DocBlock
     */
    public function tagExample($location = null, $description = null, $startLine = null, $numberOfLines = null, $inline = false)
    {
        $this->checkLocationSignature($numberOfLines, $startLine);
        $segments = [$location, $startLine, $numberOfLines, $description];

        return $this->make('example', $segments, $inline);
    }

    /**
     * @return DocBlock
     */
    public function tagFilesource()
    {
        return $this->make('filesource');
    }

    /**
     * @param string $description
     * @return DocBlock
     */
    public function tagIgnore($description = null)
    {
        return $this->make('ignore', [$description]);
    }

    /**
     * @param string $description
     * @param bool $inline
     * @return DocBlock
     */
    public function tagInternal($description = null, $inline = false)
    {
        return $this->make('internal', [$description], $inline, '{%s }}');
    }

    /**
     * @param string $url
     * @param string $name
     * @return DocBlock
     */
    public function tagLicense($url = null, $name = null)
    {
        return $this->make('license', [$url, $name]);
    }

    /**
     * @param string $url
     * @param string $description
     * @param bool $inline
     * @return DocBlock
     */
    public function tagLink($url = null, $description = null, $inline = false)
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
    public function tagMethod($name, array $args = null, $return = null, $description = null)
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
    public function tagMethodObj(MethodInterface $method)
    {
        return $this->make('method', [$method->getOutput()]);
    }

    /**
     * @param string $name
     * @return DocBlock
     */
    public function tagPackage($name = null)
    {
        return $this->make('package', [$name]);
    }

    /**
     * @param string $name
     * @param mixed $type string|array
     * @param string $description
     * @return DocBlock
     */
    public function tagParam($name, $type = null, $description = null)
    {
        return $this->variable('param', $name, $type, $description);
    }

    /**
     * @param string $name
     * @param mixed $type string|array
     * @param string $description
     * @return DocBlock
     */
    public function tagProperty($name, $type = null, $description = null)
    {
        return $this->variable('property', $name, $type, $description);
    }

    /**
     * @param string $name
     * @param mixed $type string|array
     * @param string $description
     * @return DocBlock
     */
    public function tagPropertyRead($name, $type = null, $description = null)
    {
        return $this->variable('property-read', $name, $type, $description);
    }

    /**
     * @param string $name
     * @param mixed $type string|array
     * @param string $description
     * @return DocBlock
     */
    public function tagPropertyWrite($name, $type = null, $description = null)
    {
        return $this->variable('property-write', $name, $type, $description);
    }

    /**
     * @param mixed $type string|array
     * @param string $description
     * @return DocBlock
     */
    public function tagReturn($type = null, $description = null)
    {
        $type = $this->parseType($type);

        return $this->make('return', [$type, $description]);
    }

    /**
     * @param string $target URI|FQSEN
     * @param string $description
     * @return DocBlock
     */
    public function tagSee($target = null, $description = null)
    {
        return $this->make('see', [$target, $description]);
    }

    /**
     * @param string $version
     * @param string $description
     * @return DocBlock
     */
    public function tagSince($version = null, $description = null)
    {
        return $this->make('since', [$version, $description]);
    }

    /**
     * @param string $description
     * @param int $startLine
     * @param int $numberOfLines
     * @param bool $inline
     * @return DocBlock
     */
    public function tagSource($description = null, $startLine = null, $numberOfLines = null, $inline = false)
    {
        $this->checkLocationSignature($numberOfLines, $startLine);
        $segments = [$startLine, $numberOfLines, $description];

        return $this->make('source', $segments, $inline);
    }

    /**
     * @param string $name
     * @return DocBlock
     */
    public function tagSubpackage($name = null)
    {
        return $this->make('subpackage', [$name]);
    }

    /**
     * @param mixed $type string|array
     * @param string $description
     * @return DocBlock
     */
    public function tagThrows($type = null, $description = null)
    {
        $type = $this->parseType($type);

        return $this->make('throws', [$type, $description]);
    }

    /**
     * @param string $description
     * @return DocBlock
     */
    public function tagTodo($description = null)
    {
        return $this->make('todo', [$description]);
    }

    /**
     * @param string $fqsen
     * @param string $description
     * @return DocBlock
     */
    public function tagUses($fqsen = null, $description = null)
    {
        return $this->make('uses', [$fqsen, $description]);
    }

    /**
     * @param string $name
     * @param mixed $type string|array
     * @param string $description
     * @return DocBlock
     */
    public function tagVar($name, $type = null, $description = null)
    {
        return $this->variable('var', $name, $type, $description);
    }

    /**
     * @param string $version
     * @param string $description
     * @return DocBlock
     */
    public function tagVersion($version = null, $description = null)
    {
        return $this->make('version', [$version, $description]);
    }

    /**
     * @param string $value
     * @return DocBlock
     */
    public function text($value)
    {
        return $this->make(null, [$value]);
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

        return $this->make(null, [$section]);
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        $sections = $this->processSections();
        array_unshift($sections, self::MARK_BLOCK_BEGIN);
        array_push($sections, ' ' . self::MARK_BLOCK_END);

        return implode("\n", $sections);
    }

    /**
     * @return string
     */
    public function __toString()
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

    /**
     * @param array $segments
     * @return string
     */
    protected function union(array $segments = [])
    {
        $segments = array_filter($segments);

        return implode(' ', $segments);
    }

    /**
     * @param $section
     */
    protected function push($section)
    {
        array_push($this->sections, $section);
    }

    /**
     * @param $count
     * @return string
     */
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
     * @param mixed $type string|array
     * @param string $description
     * @return DocBlock
     */
    protected function variable($tag, $name, $type = null, $description = null)
    {
        $type = $this->parseType($type);

        return $this->make($tag, [$type, '$' . $name, $description]);
    }

    /**
     * @param mixed $type string|array
     * @return string
     */
    protected function parseType($type)
    {
        if (is_array($type)) {
            $type = implode('|', $type);
        }

        return $type;
    }

    /**
     * @param string $section
     * @return string
     */
    protected function processSection($section)
    {
        $lines = explode("\n", $section);

        array_walk($lines, function (& $line) {
            $line = $this->formatToDocLine($line);
        });

        return implode("\n", $lines);
    }

    /**
     * @param string $input
     * @return string
     */
    protected function formatToDocLine($input)
    {
        return ' ' . self::MARK_LINE . ' ' . trim($input);
    }

    /**
     * @param int $numberOfLines
     * @param int $startLine
     * @throws \LogicException
     */
    protected function checkLocationSignature($numberOfLines = null, $startLine = null)
    {
        if ($numberOfLines !== null && $startLine === null) {
            throw new \LogicException('If you specify <numberOfLines> you must specify <startLine>');
        }
    }

} 