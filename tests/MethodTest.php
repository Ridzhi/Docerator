<?php

namespace DocBuilder;


class MethodTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Method $inst
     */
    protected $inst;
    /**
     * @var \ReflectionClass $reflectionClass
     */
    protected $reflectionClass;

    public function setUp()
    {
        $this->inst = new Method('test');
        $this->reflectionClass = new \ReflectionClass($this->inst);
    }

    public function testGetOutput()
    {
        $this->inst->setReturn('string');
        $this->inst->setArgument(new Argument('string:name=\'player\''));
        $this->inst->setArgument(new Argument('int:age'));
        $this->inst->setDescription('Description test method');
        $output = $this->inst->getOutput();
        $this->assertEquals('string test(string $name = \'player\', int $age) Description test method', $output);
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

    public function testParseTypeString()
    {
        $method = $this->reflectionClass->getMethod('parseType');
        $method->setAccessible(true);
        $actual = $method->invoke($this->inst, 'SomeClass');
        $this->assertEquals('SomeClass', $actual);
    }

    public function testParseTypeArray()
    {
        $method = $this->reflectionClass->getMethod('parseType');
        $method->setAccessible(true);
        $actual = $method->invoke($this->inst, ['array', 'string']);
        $this->assertEquals('array|string', $actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseTypeNotArrayOrString()
    {
        $method = $this->reflectionClass->getMethod('parseType');
        $method->setAccessible(true);
        $method->invoke($this->inst, 7);
    }

    public function getSignatureProvider()
    {
        return [
            [[], '()'],
            [[new Argument('name')], '($name)'],
            [[new Argument('string:name')], '(string $name)'],
            [[new Argument('string:name'), new Argument('int:age')], '(string $name, int $age)']
        ];
    }

} 