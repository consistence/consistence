Strict Types
============

PHP contains by default a lot of magic behavior and checks almost nothing. When particular type is expected, PHP tries to convert given type to the expected one, which can yield unexpected results. Consistence promotes using strict types to achieve more predictable, maintainable and readable code. PHP 7 helped a lot with these problems by introducing strict types, but only for function/method parameters and return types. Following tools should provide additional help:

Strict Object
-------------

Objects in [OOP](https://en.wikipedia.org/wiki/Object-oriented_programming) are defined as encapsulating data and behavior. In PHP data is represented as object properties and behavior as object methods.

PHP does not check that you defined properties which are used:

```php
<?php

class Foo
{

}

$foo = new Foo();
$foo->bar = 'bar';
var_dump($foo->bar); // 'bar'
```

This can lead to bugs which are difficult to discover, because there are no errors, warnings or notices and the bug can be introduced just by a typo.

This behavior is part of the [PHP's magic methods](http://php.net/manual/en/language.oop5.magic.php). Using these methods usually leads to breaking encapsulation of objects. You can stop this by overloading these methods, which is exactly what Consistence does:

```php
<?php

class Foo extends \Consistence\ObjectPrototype
{

}

$foo = new Foo();

// \Consistence\UndefinedPropertyException: Property Foo::$bar is not defined or is not accessible
$foo->bar = 'bar';
```

 There are several ways, how you can use this in your code:

* [`ObjectPrototype`](/src/ObjectPrototype.php) is abstract class which you can extend by default as showed above, this is the recommended way
* if you can't (or do not want to) extend, you can use [`ObjectMixinTrait`](/src/Type/ObjectMixinTrait.php)
* if you want to override only some magic methods, or need to implement some magic functionality, but want this as a fallback use methods in [`ObjectMixin`](/src/Type/ObjectMixin.php)

Checking value types
--------------------

You can't always check types with type hints, sometimes you need to check the types inside of method or need to do a more complex check.

With `Type::checkType()` you can start with simple check such as:

```php
<?php

use Consistence\Type\Type;

Type::checkType('foo', 'string');

// \Consistence\InvalidArgumentTypeException: integer expected, foo [string] given
Type::checkType('foo', 'integer');
```

Usable types are listed in the [Consistence Coding Standard](https://github.com/consistence/coding-standard/blob/master/consistence-coding-standard.md#allowed-types-for-param-return-var).

Also note that for custom type always write the FQN (fully qualified name) and do not use leading `\`

When checking custom types, subtypes are allowed to be passed in by default, but you can control this by the third parameter:

```php
<?php

use Consistence\Type\Type;

Type::checkType(new DateTimeImmutable(), DateTimeInterface::class);

// \Consistence\InvalidArgumentTypeException: DateTimeInterface expected, DateTimeImmutable#ecc7 [DateTimeImmutable] given
Type::checkType(new DateTimeImmutable(), DateTimeInterface::class, Type::SUBTYPES_DISALLOW);
```

There is also `Type::hasType()` which can be used in conditions or anywhere else where you do not want to throw the exceptions.

### Arrays/Collections

Arrays/Collections are noted as `<type>[]`:

```php
<?php

use Consistence\Type\Type;

Type::checkType(
	[
		'foo',
	],
	'string[]'
);

// \Consistence\InvalidArgumentTypeException: string[] expected, 1 [integer] given
Type::checkType(1, 'string[]');
```

This can also be used to check nested arrays:

```php
<?php

use Consistence\Type\Type;

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

### Union types

There are a lot of cases when multiple types can be accepted, this can be represented by union types which are noted with `|`:

```php
<?php

use Consistence\Type\Type;

Type::checkType('foo', 'string|integer');
Type::checkType(1, 'string|integer');
```

Getting types
-------------

You can use `Type::getType()` to get type from value:

```php
<?php

use Consistence\Type\Type;

var_dump(Type::getType('foo')); // string
var_dump(Type::getType(['foo'])); // array
var_dump(Type::getType(new DateTimeImmutable())); // DateTimeImmutable
```
