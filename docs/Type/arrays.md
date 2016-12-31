Arrays
======

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

PHP has a lot of [array-manipulation functions](http://php.net/manual/en/ref.array.php), but using them is difficult, because they do not have unified API and never throw any Exceptions, errors are reported only through return values. These functions also use non-strict comparisons while searching by default. This is all in contradiction to the basic Consistence rules.

[`ArrayType`](/src/Type/ArrayType/ArrayType.php) contains the most used operations and always offers several variants of the methods to suit your needs.

Consistent API
--------------

PHP's function argument differs from function to function, complicating both writing and readability. In Consistence's [`ArrayType`](/src/Type/ArrayType/ArrayType.php) the first parameter is always the array which will be acted upon.

Strict by default
-----------------

PHP's functions use non-strict comparisons by default, which can lead to unexpected results:

```php
<?php

$haystack = ['1', '2', '3'];
$needle = true;
var_dump(array_search($needle, $haystack)); // 0 -> found '1'
```

Searching for `true` will also find `'1'` because it is "trueish" by PHP's [type juggling](http://php.net/manual/en/language.types.type-juggling.php). You can always add third parameter to use strict searching with [`array_search`](http://php.net/manual/en/function.array-search.php#refsect1-function.array-search-parameters), but remembering to write it everywhere is not comfortable, is error prone and less readable (when using boolean parameters it is not obvious what is the parameter from the function call):

```php
<?php

$haystack = ['1', '2', '3'];
$needle = true;
var_dump(array_search($needle, $haystack, true)); // false -> not found
```

With Consistence, the comparison is always strict by default:

```php
<?php

use Consistence\Type\ArrayType\ArrayType;

$haystack = ['1', '2', '3'];
$needle = true;
var_dump(ArrayType::findKey($haystack, $needle)); // null -> not found
```

Finding vs getting
------------------

Consistence follows naming pattern for searching, computing, etc. methods:

* methods starting with `get` must always return the expected value and if it is not found / cannot be computed etc., or throw an Exception (this does not include "getters" on value objects)
* methods starting with `find` return the expected value and if the value is not available, ten return `null`

Advantage of this naming pattern is that you can see from the method call if you need to handle the return value in a special way.

When working with arrays, where there is possibility, that a key or value which you are expecting is not present, you should always check if they are present before using them. This means checking this with ifs and throwing an error, which is exactly what you can achieve with `getValue` and `getKey` (or their modifications):

```php
<?php

$haystack = [1, 2, 3];
if (!isset($haystack['foo'])) {
	throw new \Exception('Missing key foo');
}
var_dump($haystack['foo']);
```

can be transformed to:

```php
<?php

use Consistence\Type\ArrayType\ArrayType;

$haystack = [1, 2, 3];
var_dump(ArrayType::getValue($haystack, 'foo')); // \Consistence\Type\ArrayType\ElementDoesNotExistException
```

This is again decoupling cumbersome data manipulation logic and leaving only important business logic.

KeyValuePair
------------

Callback operations in PHP usually work only with array values, but sometimes it is needed to perform the operation using keys or both the combination of key and value. For example [`array_filter`](http://php.net/manual/en/function.array-filter.php#refsect1-function.array-filter-parameters) uses third argument to influence this by flags, but it is again not very clear interface.

Consistence brings [`KeyValuePair`](/src/Type/ArrayType/KeyValuePair.php) which is used in callbacks to operate on both and be clear about the arguments given. With `ArrayType::mapByCallback()` you can map both keys and values:

```php
<?php

use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\ArrayType\KeyValuePair;

$haystack = [
	1 => 1,
	2 => 2,
	3 => 3,
];
var_dump(ArrayType::mapByCallback($haystack, function (KeyValuePair $pair) {
	return new KeyValuePair(
		2 * $pair->getKey(),
		3 * $pair->getValue()
	);
}));
/*
[
	2 => 3,
	4 => 6,
	6 => 9,
]
*/
```

To avoid creating large number of objects you can use [`KeyValuePairMutable`](/src/Type/ArrayType/KeyValuePairMutable.php) which will change the given instance:

```php
<?php

use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\ArrayType\KeyValuePairMutable;

$haystack = [
	1 => 1,
	2 => 2,
	3 => 3,
];
var_dump(ArrayType::mapByCallback($haystack, function (KeyValuePairMutable $pair) {
	$pair->setPair(
		2 * $pair->getKey(),
		3 * $pair->getValue()
	);
	return $pair;
}));
/*
[
	2 => 3,
	4 => 6,
	6 => 9,
]
*/
```

[`KeyValuePair`](/src/Type/ArrayType/KeyValuePair.php) is also used in `ArrayType::findByCallback()` and `ArrayType::getByCallback()` which will return the pair so that you can work with it further, if you need both values in your algorithm. You can of course also use [`KeyValuePair`](/src/Type/ArrayType/KeyValuePair.php) as a value object in your algorithms to represent key and value in a single.
