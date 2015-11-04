<?php

//TODO: @ignore
use DocBuilder\DocBuilder;

class DocBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DocBuilder
     */
    protected $inst;
    /**
     * @var \ReflectionClass
     */
    protected $reflectionClass;

    protected $section;

    public function setUp()
    {
        $this->inst = new DocBuilder();
        $this->reflectionClass = new ReflectionClass($this->inst);
    }

    public function testMake()
    {
        $method = $this->reflectionClass->getMethod('make');
        $method->setAccessible(true);
        $method->invoke($this->inst, 'SomeTag', ['segment1', 'segment2', 'segment3']);
        $this->assertEquals('@SomeTag segment1 segment2 segment3', $this->getSectionValue());
    }

    /**
     * @dataProvider getLineBreakSequenceProvider
     */
    public function testGetLineBreakSequence($count, $expected)
    {
        $method = $this->reflectionClass->getMethod('getLineBreakSequence');
        $method->setAccessible(true);
        $method->invoke($this->inst, $count);
        $this->assertEquals($expected, $expected);
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
    public function testExampleLogicException()
    {
        $method = $this->reflectionClass->getMethod('getLineBreakSequence');
        $method->setAccessible(true);
        $method->invoke($this->inst, 'some_location', 'some description', null, 30);
    }


    public function testConvertToDocLine()
    {
        $method = $this->reflectionClass->getMethod('convertToDocLine');
        $method->setAccessible(true);
        $input = 'This true doc line';
        $actual = $method->invoke($this->inst, $input);
        $this->assertEquals('* ' . $input, $actual);
    }

    public function getLineBreakSequenceProvider()
    {
        return [
            [1, "\n"],
            [2, "\n\n"],
            [4, "\n\n\n\n"]
        ];
    }

    protected function getSectionValue()
    {
        $property = $this->reflectionClass->getProperty('sections');
        $property->setAccessible(true);
        return $property->getValue($this->inst)[0];
    }

} 