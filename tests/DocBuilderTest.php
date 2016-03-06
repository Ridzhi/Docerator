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
        $method = $this->reflectionClass->getMethod('example');
        $method->invoke($this->inst, 'some_location', 'some description', null, 30);
    }

    /**
     * @expectedException \LogicException
     */
    public function testSourceLogicException()
    {
        $method = $this->reflectionClass->getMethod('source');
        $method->setAccessible(true);
        $method->invoke($this->inst, 'some description', null, 30);
    }

    public function testFormatToDocLine()
    {
        $method = $this->reflectionClass->getMethod('formatToDocLine');
        $method->setAccessible(true);
        $input = 'Some text ';
        $actual = $method->invoke($this->inst, $input);
        $this->assertEquals(' * Some text', $actual);
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