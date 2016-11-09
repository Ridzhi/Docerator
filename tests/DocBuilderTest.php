<?php

namespace DocBuilder;


class DocBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DocBlock
     */
    protected $inst;
    /**
     * @var \ReflectionClass
     */
    protected $reflectionClass;

    protected $section;

    public function setUp()
    {
        $this->inst = new DocBlock();
        $this->reflectionClass = new \ReflectionClass($this->inst);
    }

    public function testMake()
    {
        $method = $this->reflectionClass->getMethod('make');
        $method->setAccessible(true);
        $method->invoke($this->inst, 'SomeTag', ['segment1', 'segment2', 'segment3']);
        $this->assertEquals('@SomeTag segment1 segment2 segment3', $this->getSectionValue());
    }


    public function testGetLineBreakSequence()
    {
        $method = $this->reflectionClass->getMethod('getLineBreakSequence');
        $method->setAccessible(true);

        $actual = $method->invoke($this->inst, 3);
        $this->assertEquals("\n\n\n", $actual);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetLineBreakSequenceLogicException()
    {
        $method = $this->reflectionClass->getMethod('getLineBreakSequence');
        $method->setAccessible(true);
        $method->invoke($this->inst, 0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testCheckLocationSignature()
    {
        $method = $this->reflectionClass->getMethod('checkLocationSignature');
        $method->setAccessible(true);
        $method->invoke($this->inst, 30, null);
    }

    public function testFormatToDocLine()
    {
        $method = $this->reflectionClass->getMethod('formatToDocLine');
        $method->setAccessible(true);
        $input = 'Some text ';
        $actual = $method->invoke($this->inst, $input);
        $this->assertEquals(' * Some text', $actual);
    }

    protected function getSectionValue()
    {
        $property = $this->reflectionClass->getProperty('sections');
        $property->setAccessible(true);
        return $property->getValue($this->inst)[0];
    }

} 