<?php

namespace DocBuilder;


class MethodTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Method $inst
     */
    protected $inst;
    /**
     * @var ReflectionClass $reflectionClass
     */
    protected $reflectionClass;

    public function setUp()
    {
        $this->inst = new Method('test');
        $this->reflectionClass = new \ReflectionClass($this->inst);
    }

    /**
     * @dataProvider setArgumentProvider
     */
    public function testSetArgument($name, $type, $expected)
    {
        $this->inst->setArgument($name, $type);
        $property = $this->reflectionClass->getProperty('args');
        $property->setAccessible(true);
        $actual = $property->getValue($this->inst);
        $this->assertEquals($expected, $actual);
    }

    public function testGetOutput()
    {
        $this->inst->setReturn('string');
        $this->inst->setArgument('name', 'string');
        $this->inst->setArgument('age', 'int');
        $this->inst->setDescription('Description test method');
        $output = $this->inst->getOutput();
        $this->assertEquals('string test(string $name, int $age) Description test method', $output);
    }

    /**
     * @dataProvider getSignatureProvider
     */
    public function testGetSignature($args, $expected)
    {
        $property = $this->reflectionClass->getProperty('args');
        $property->setAccessible(true);
        $property->setValue($this->inst, $args);

        $method = $this->reflectionClass->getMethod('getSignature');
        $method->setAccessible(true);
        $actual = $method->invoke($this->inst);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider parseReturnProvider
     */
    public function testParseReturn($input, $expected)
    {
        $method = $this->reflectionClass->getMethod('parseReturn');
        $method->setAccessible(true);
        $actual = $method->invoke($this->inst, $input);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseReturnException()
    {
        $method = $this->reflectionClass->getMethod('parseReturn');
        $method->setAccessible(true);
        $method->invoke($this->inst, 7);
    }

    public function parseReturnProvider()
    {
        return [
            [null, 'void'],
            ['string', 'string'],
            [['string', 'array', 'null'], 'string|array|null']
        ];
    }

    public function setArgumentProvider()
    {
        return [
            ['name', null, ['$name']],
            ['name', 'string', ['string $name']],
        ];
    }

    public function getSignatureProvider()
    {
        return [
            [[], '()'],
            [['$name'], '($name)'],
            [['string $name'], '(string $name)'],
            [['string $name', 'int $age'], '(string $name, int $age)']
        ];
    }

} 