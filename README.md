#Docerator - create php doc block for generated programs.

##Usage

Docerator supports all tags from http://www.phpdoc.org.

```
<?php
$doc = new \Docerator\Docerator();

$doc
    ->text('Some text')
    ->api()
    ->author('Danila Ridzhi', 'danilaridzhi@gmail.com')
    ->category('description')
    ->copyright('description')
    ->deprecated('1.0', 'description')
    ->example('somefile.php', 'description', 4, 10)
    ->filesource()
    ->ignore('description')
    ->internal('description')
    ->license('https://opensource.org/licenses/MIT', 'MIT')
    ->link('project.com', 'description')
    ->method('getName')
    ->package('Docerator\\Docerator')
    ->param('name', 'string|array', 'description')
    ->property('name', 'string|array', 'description')
    ->propertyRead('name', 'string|array', 'description')
    ->propertyWrite('name', 'string|array', 'description')
    ->returnTag('string|array', 'description')
    ->see('Docerator::see()', 'description')
    ->since('1.0', 'description')
    ->source('description', 1, 2)
    ->subpackage('\Docerator\Method')
    ->throwsTag('Exception', 'description')
    ->emptyLine(2)
    ->uses('Docerator::uses()', 'description')
    ->varTag('name', 'string|array', 'description')
    ->version('1.0', 'description');

// Output
echo $doc;
// or
$output = $doc->getOutput();
echo $output;
```

##Details

###Method tag

#### Inline syntax
```
<?php
$doc = new Docerator();
$doc->method('getAge', ['string:name=\'username\''], 'int', 'Get user age');
```

####Object syntax (Method object helper)

```
<?php

$method = new Method('getAge');
$method
    ->setArgument(new \Docerator\Argument('string:name=\'username\''))
    ->setReturn('int')
    ->setDescription('Get user age');

$doc = new Docerator();
$doc->methodObj($method);

```
###Argument helper
Argument object constructor can get special expression looks like varType:varName=varDefaultValue,
where varType and varDefaultValue is not required. Also you can use classic oop approach.
```
// special expression
$arg = new \Docerator\Argument('string:name=\'username\'');
// classic
$arg = new \Docerator\Argument();
$arg
    ->setName('name')
    ->setType('string')
    ->setDefault('\'username\'');
```

###Type definition
$type argument supports string|array syntax

####Example
```
<?php
$doc = new Docerator();
$doc
	->param('name', ['string', 'array'], 'description')
	->property('name', ['string', 'array'], 'description')
    ->propertyRead('name', ['string', 'array'], 'description')
    ->propertyWrite('name', ['string', 'array'], 'description')
    ->returnTag(['string', 'array'], 'description')
    ->varTag('name', ['string', 'array'], 'description');

$arg = new \Docerator\Argument();
$arg->setType(['string', 'array']);
``` 
