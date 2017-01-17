Consistence
===========

PHP offers a lot of handy functionality, but due to its organic growth this is not always easily usable. The aim of this library is to provide consistent approach to PHP's functionality. This means:

* clear and consistent naming patterns
* consistent arguments order
* errors reported as Exceptions, never as return values
* added functionality and interfaces, which are missing
* value objects representing common elements

Installation
------------

Install package [`consistence/consistence`](https://packagist.org/packages/consistence/consistence) with [Composer](https://getcomposer.org/):

```bash
composer require consistence/consistence
```

There are no further steps needed, you can start using Consistence whenever suitable in your codebase, see features below.

### Integrations

If you are using one of the following libraries/frameworks, check out these integrations:

* [Doctrine ORM integration](https://github.com/consistence/consistence-doctrine) provides integration to store Consistence value objects in database through entities, there is also [Symfony bundle with this integration](https://github.com/consistence/consistence-doctrine-symfony)
* [JMS Serializer integration](https://github.com/consistence/consistence-jms-serializer) provides integration to (de)serialize Consistence value objects, there is also [Symfony bundle with this integration](https://github.com/consistence/consistence-jms-serializer-symfony)

Documentation & features
------------------------

In following sections, there are excerpts of Consistence functionality helping in key areas along with links to dedicated documentation pages.

### [Enums and MultiEnums](docs/Enum/enums.md)

Enums represent predefined set of values. The available values are defined statically by each enum class. Each value is represented by an instance of this class in a flyweight manner. This ensures that the same values are always the same instance.

```php
<?php

class CardColor extends \Consistence\Enum\Enum
{

	const BLACK = 'black';
	const RED = 'red';

}

$red = CardColor::get(CardColor::RED);

$availableValues = CardColor::getAvailableValues(); // ['black', 'red']

function doMagicTrick(CardColor $guessedCardColor)
{
	// ...
}
```

The main advantages of using enums for representing set of values are:

* you can be sure, that the value is unchanged (not only validated once)
* you can use type hints to check that only the defined set of values is acceptable
* you can define behavior on top of the represented values

[> Learn more about Enums in Consistence](docs/Enum/enums.md)

With [MultiEnums](docs/Enum/multi-enums.md) you can even represent multiple values and operations on them in a single object.

### [Strict Types](docs/Type/strict-types.md)

PHP contains by default a lot of magic behavior and checks almost nothing. When particular type is expected, PHP tries to convert given type to the expected one, which can yield unexpected results. Consistence promotes using strict types to achieve more predictable, maintainable and readable code.

```php
<?php

class Foo extends \Consistence\ObjectPrototype
{

}

$foo = new Foo();

// \Consistence\UndefinedPropertyException: Property Foo::$bar is not defined or is not accessible
$foo->bar = 'bar';
```

```php
<?php

use Consistence\Type\Type;

Type::checkType('foo', 'string');
Type::checkType('foo', 'string|integer');
Type::checkType(1, 'string|integer');
Type::checkType(
	[
		[
			'foo',
		],
		[
			'bar',
		],
	],
	'string[][]'
);
```

[> Learn more about Strict Types in Consistence](docs/Type/strict-types.md)

### [Time Format](docs/Time/time-format.md)

Using [value objects](http://martinfowler.com/bliki/ValueObject.html) is great, because you get clear type hint and you can be sure, that the value you get is in consistent state and that it is valid (if the representation follows the principles).

[DateTime](http://php.net/manual/en/class.datetime.php) is one of the few value objects which are natively in PHP. Unfortunately, PHP again prefers using magic behavior over clear errors by default and there is also quite a lot of commonly required functionality missing, so Consistence tries to help with these shortcomings.

```php
<?php

use Consistence\Time\TimeFormat;

// example of strict validation of format:

// H requires leading zero, but there is none
// \Consistence\Time\TimeDoesNotMatchFormatException: Time "2:30" does not match format "H:i"
TimeFormat::createDateTimeFromFormat('H:i', '2:30');

// example of strict validation of values:

// there is no 25th hour in the day
// \Consistence\Time\TimeDoesNotExistException: Time given in "25:00" does not exist
TimeFormat::createDateTimeFromFormat('H:i', '25:00');
```

[> Learn more about Time Formats in Consistence](docs/Time/time-format.md)

### [Arrays](docs/Type/arrays.md)

Arrays in PHP are combination of traditional [lists](https://en.wikipedia.org/wiki/List_(abstract_data_type)) and [dictionaries](https://en.wikipedia.org/wiki/Associative_array) and therefore have a wide range of applications. Because they are so common, there are a lot of repeating operations for manipulation of these data structures. These operations should be abstracted away into function, so that they can be reused and the code contains only essential business logic.

Simple finding of a value by a custom rule (*"find values shorter than five characters"*) can be cumbersome:

```php
<?php

$haystack = ['lorem', 'ipsum', 'dolor', 'sit', 'amet'];
foreach ($haystack as $value) {
	if (strlen($value) < 5) {
		// do something with value
	}
}
```

In the previous example, there is logic of going through the array mixed with business logic. This can be improved by using a dedicated method for iteration. Then only the business logic remains in the callback:

```php
<?php

use Consistence\Type\ArrayType\ArrayType;

$haystack = ['lorem', 'ipsum', 'dolor', 'sit', 'amet'];
$value = ArrayType::findValueByCallback($haystack, function($value) {
	return strlen($value) < 5;
});
if ($value !== null) {
	// do something with value
}
```

PHP has a lot of [array-manipulation functions](http://php.net/manual/en/ref.array.php), but using them is difficult, because they do not have unified API and never throw any Exceptions, errors are reported only through return values. These functions also use non-strict comparisons while searching by default. Consistence tries to solve those issues.

[> Learn more about Arrays in Consistence](docs/Type/arrays.md)
