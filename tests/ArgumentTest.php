<?php

namespace Docerator;


class ArgumentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider parseProvider
     */
    public function testParse($expression, $type, $name, $default)
    {
        $arg = new Argument();
        $ref = new \ReflectionClass($arg);
        $method = $ref->getMethod('parse');
        $method->setAccessible(true);
        $actual = $method->invoke($arg, $expression);
        $expected = [$type, $name, $default];
        $this->assertEquals($expected, $actual);
    }


    public function parseProvider()
    {
        return [
            ['id', '', 'id', Argument::DEFAULT_NOT_ENABLED],
            ['int:id', 'int', 'id', Argument::DEFAULT_NOT_ENABLED],
            ['int:id=404', 'int', 'id', 404]
        ];
    }

}