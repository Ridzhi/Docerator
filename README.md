#DocBuilder - build php doc for generated programs.

##Usage

DocBuilder supports all tags from http://www.phpdoc.org.

```php
<?php

$doc = new \DocBuilder\DocBlock();

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
    ->package('DocBlock')
    ->param('name', 'string|array', 'description')
    ->property('name', 'string|array', 'description')
    ->propertyRead('name', 'string|array', 'description')
    ->propertyWrite('name', 'string|array', 'description')
    ->returnTag('string|array', 'description')
    ->see('DocBlock', 'description')
    ->since('1.0', 'description')
    ->source('description', 1, 2)
    ->subpackage('\DocBuilder\Method')
    ->throwsTag('Exception', 'description')
    ->emptyLine(2)
    ->uses('DocBlock', 'description')
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
```php
<?php

$doc = new \DocBuilder\DocBlock();
$doc->method('getAge', ['string:name=\'username\''], 'int', 'Get user age');
```

####Object syntax (Method object helper)

```php
<?php

$method = new \DocBuilder\Method('getAge');
$method
    ->setArgument(new \DocBuilder\Argument('string:name=\'username\''))
    ->setReturn('int')
    ->setDescription('Get user age');

$doc = new \DocBuilder\DocBlock();
$doc->methodObj($method);

```
###Argument helper
Argument object constructor can get special expression looks like varType:varName=varDefaultValue,
where varType and varDefaultValue is not required. Also you can use classic oop approach.
```php
<php

// special expression
$arg = new \DocBuilder\Argument('string:name=\'username\'');
// classic
$arg = new \DocBuilder\Argument();
$arg
    ->setName('name')
    ->setType('string')
    ->setDefault('\'username\'');
```

###Type definition
$type argument also supports array syntax

####Example
```php
<?php

$doc = new \DocBuilder\DocBlock();
$doc
	->param('name', ['string', 'array'], 'description')
	->property('name', ['string', 'array'], 'description')
    ->propertyRead('name', ['string', 'array'], 'description')
    ->propertyWrite('name', ['string', 'array'], 'description')
    ->returnTag(['string', 'array'], 'description')
    ->varTag('name', ['string', 'array'], 'description');

$arg = new \DocBuilder\Argument();
$arg->setType(['string', 'array']);
``` 
