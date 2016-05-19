#DocBuilder - builder of phpdoc blocks.

##Usage

DocBuilder supports all tags from http://www.phpdoc.org.

```php
$doc = new \DocBuilder\DocBlock();

$doc
    ->text('Some text')
    //all tag methods has prefix tag
    ->tagApi()
    ->tagAuthor('Danila Ridzhi', 'danilaridzhi@gmail.com')
    ->tagCategory('description')
    ->tagCopyright('description')
    ->tagDeprecated('1.0', 'description')
    ->tagExample('somefile.php', 'description', 4, 10)
    ->tagFilesource()
    ->tagIgnore('description')
    ->tagInternal('description')
    ->tagLicense('https://opensource.org/licenses/MIT', 'MIT')
    ->tagLink('project.com', 'description')
    ->tagMethod('getName')
    ->tagPackage('DocBlock')
    ->tagParam('name', 'string|array', 'description')
    ->tagProperty('name', 'string|array', 'description')
    ->tagPropertyRead('name', 'string|array', 'description')
    ->tagPropertyWrite('name', 'string|array', 'description')
    ->tagReturn('string|array', 'description')
    ->tagSee('DocBlock', 'description')
    ->tagSince('1.0', 'description')
    ->tagSource('description', 1, 2)
    ->tagSubpackage('\DocBuilder\Method')
    ->tagThrows('Exception', 'description')
    ->tagUses('DocBlock', 'description')
    ->tagVar('name', 'string|array', 'description')
    ->tagVersion('1.0', 'description')
    //and helpers
    ->emptyLine(2)
    ->text('Some text')


// Output use magic
echo $doc;
// or not
echo $doc->getOutput();
```

##Details

###Method tag

#### Inline syntax
```php
$doc->tagMethod('getAge', ['string:name=\'username\''], 'int', 'Get user age');
```

####Object syntax (Method object helper)

```php
$method = new \DocBuilder\Method('getAge');
$method
    ->setArgument(new \DocBuilder\Argument('string:name=\'username\''))
    ->setReturn('int')
    ->setDescription('Get user age');

$doc = new \DocBuilder\DocBlock();
$doc->tagMethodObj($method);
```
###Argument helper
Argument object constructor can get special expression looks like varType:varName=varDefaultValue,
where varType and varDefaultValue is not required. Also you can use setters.

```php
// special expression
$arg = new \DocBuilder\Argument('string:name=\'username\'');
// setters
$arg = new \DocBuilder\Argument();
$arg
    ->setName('name')
    ->setType('string')
    ->setDefault('\'username\'')
    // or use string helper
    ->setDefaultAsString('username');
```

###Type definition
Data type argument can be pass as string or array (in all methods, which use data type arg).

```php
$doc
	->tagParam('name', 'string array', 'description')
	//or
	->tagParam('name', ['string', 'array'], 'description');

$arg = new \DocBuilder\Argument();
$arg
	->setType('string array')
	//or
	->setType(['string', 'array']);
``` 
