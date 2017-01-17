MultiEnums
==========

[Enums](enums.md) represent predefined set of values. MultiEnum represents multiple boolean values and provides set operations on top of them. All of the values are stored in just one integer using [bit masks](https://en.wikipedia.org/wiki/Mask_(computing)).

In MultiEnum each stored value is represented by a bit - that is why only set of booleans can be represented this way. The final value is composed adding together all the enabled bits. For this to work all **the values must be associated with unique powers of two** (1, 2, 4, 8, 16, 32, …) as seen in the example below:

> **Important:** The values must be unique powers of 2.

```php
<?php

class RolesEnum extends \Consistence\Enum\MultiEnum
{

	const USER = 1;
	const EMPLOYEE = 2;
	const ADMIN = 4;

}

$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
```

> **Note:**  In the context of this article "ordinary" [enums](enums.md) will by called "single Enums" to distinguish them from MultiEnums. When it is not important, if it is a single Enum or MultiEnum, then "enum" will be used.

Creating MultiEnum instances
----------------------------

There are several ways how to get a particular instance:

```php
<?php

$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN); // list values as parameters in getMulti
$userAndAdmin = RolesEnum::getMultiByArray([RolesEnum::USER, RolesEnum::ADMIN]); // array of values as parameters in getMultiByArray
```

If you already have a "multi" value (for example it was stored in database), you can also use directly `get` as with single Enums:

```php
<?php

$userAndAdmin = RolesEnum::get(5); // creating by value, which represents user(1) and admin(4)
$userAndAdmin = RolesEnum::get(RolesEnum::USER | RolesEnum::ADMIN); // directly using bitwise operations
```

Comparing and checking values
-----------------------------

[Comparing](enums.md#comparing) and [checking](enums.md#checking-values) work the same way as with single Enums. Note, that the value compared/checked is the representation of the MultiEnum instance i.e. the value of the composed bit mask, not any single value (if it matches a single value, then only this value is enabled).

MultiEnum set operations
------------------------

On top of operations (comparing, checking values) of single Enums, MultiEnums use set operations.

General rules:

* all enums should be [immutable](https://en.wikipedia.org/wiki/Immutable_object), so all "modify" operations do not actually change the object, but instead return a new instance representing the resulting value
* when working with values, only single values are accepted (`get` method is an exception)
* order of value arguments or operations is not important, because everything is represented by resulting value, returning the same instance for the same value

### Contains

```php
<?php

$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

// checking MultiEnum with single value
$userAndAdmin->contains(RolesEnum::get(RolesEnum::USER)); // true
$userAndAdmin->contains(RolesEnum::get(RolesEnum::ADMIN)); // true
$userAndAdmin->contains(RolesEnum::get(RolesEnum::EMPLOYEE)); // false

// checking MultiEnum with multiple values - testing subsets
$userAndAdmin->contains(RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN)); // true
$userAndAdmin->contains(RolesEnum::getMulti(RolesEnum::ADMIN, RolesEnum::USER)); // true (order is not important)
$userAndAdmin->contains(RolesEnum::getMulti(RolesEnum::USER, RolesEnum::EMPLOYEE)); // false

// checking value - only valid single values are accepted
$userAndAdmin->containsValue(RolesEnum::USER); // true
$userAndAdmin->containsValue(RolesEnum::ADMIN); // true
$userAndAdmin->containsValue(RolesEnum::EMPLOYEE); // false
$userAndAdmin->containsValue(5); // Consistence\Enum\InvalidEnumValueException: 5 [integer] is not a valid value, accepted values: 1, 2, 4
```

### Add

```php
<?php

// adding MultiEnums
$empty = RolesEnum::getMulti();
$user = $empty->add(RolesEnum::get(RolesEnum::USER));
$userAndAdminAndEmployee = $empty->add(RolesEnum::getMulti(RolesEnum::ADMIN, RolesEnum::EMPLOYEE));
$userAndAdminAndEmployee = $userAndAdminAndEmployee->add(RolesEnum::getMulti(RolesEnum::USER)); // same value as before, user was already included

// adding by value
$empty = RolesEnum::getMulti();
$user = $empty->addValue(RolesEnum::USER);
$userAndAdmin = $user->addValue(RolesEnum::ADMIN);

// when using value, only valid single values are accepted
$empty->addValue(RolesEnum::USER | RolesEnum::EMPLOYEE); // Consistence\Enum\InvalidEnumValueException: 3 [integer] is not a valid value, accepted values: 1, 2, 4
```

### Remove

```php
<?php

// removing MultiEnums
$userAndAdminAndEmployee = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN, RolesEnum::EMPLOYEE);
$userAndAdmin = $userAndAdminAndEmployee->remove(RolesEnum::get(RolesEnum::EMPLOYEE));
$userAndAdmin = $userAndAdminAndEmployee->remove(RolesEnum::get(RolesEnum::EMPLOYEE)); // same value as before, employee was already missing
$empty = $userAndAdmin->remove(RolesEnum::getMulti(RolesEnum::ADMIN, RolesEnum::EMPLOYEE));

// removing by value
$userAndAdminAndEmployee = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN, RolesEnum::EMPLOYEE);
$userAndAdmin = $userAndAdminAndEmployee->removeValue(RolesEnum::EMPLOYEE);
$user = $userAndAdmin->removeValue(RolesEnum::ADMIN);

// when using value, only valid single values are accepted
$userAndAdminAndEmployee->removeValue(RolesEnum::USER | RolesEnum::EMPLOYEE); // Consistence\Enum\InvalidEnumValueException: 3 [integer] is not a valid value, accepted values: 1, 2, 4
```

### Intersect

```php
<?php

// intersect MultiEnums
$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
$userAndEmployee = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::EMPLOYEE);
$user = $userAndAdmin->intersect($userAndEmployee);
$empty = $user->intersect(RolesEnum::getMulti(RolesEnum::ADMIN, RolesEnum::EMPLOYEE));

// intersect with value
$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
$user = $userAndAdmin->intersectValue(RolesEnum::USER);
$empty = $userAndAdmin->intersectValue(RolesEnum::EMPLOYEE);

// when using value, only valid single values are accepted
$user->intersectValue(RolesEnum::USER | RolesEnum::EMPLOYEE); // Consistence\Enum\InvalidEnumValueException: 3 [integer] is not a valid value, accepted values: 1, 2, 4
```

### Filter

```php
<?php

use Consistence\Type\ArrayType\ArrayType;

$allowedRoles = RolesEnum::getMulti(RolesEnum::USER);

$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
$user = $userAndAdmin->filterValues(function ($singleValue) use ($allowedRoles) {
	return ArrayType::inArray($allowedRoles->getAvailableValues(), $singleValue);
});
```

Getting values
--------------

As with [single Enum](enums.md#getting-values), you can get the value representing the MultiEnum with `getValue`, but note that the value you get is the value of the composed bit mask, not any single value (if it matches a single value, then only this value is enabled). Again this should usually not be used outside of the enum itself (for the purpose of defining custom methods) or serialization (getting values for API/UI/persistence).

```php
<?php

$user = RolesEnum::getMulti(RolesEnum::USER);
$userValue = $user->getValue(); // 1
$userAndAdmin = $user->addValue(RolesEnum::ADMIN);
$userValue = $user->getValue(); // 5
```

If you rather want to get list of all the "enabled" (true) values, then use `getValues`:

```php
<?php

$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
$values = $user->getValues(); // [1, 4]
```

comparing, checking values, single value check vs multi etc

Mapping a MultiEnum to a single Enum
------------------------------------

With single Enums you can represent a single value from a defined set and with MultiEnum, you can represent multiple enabled options from a set. You need to represent some sets as both single Enums and MultiEnums, depending on the usecases and this option allows you to make an explicit connection between these two and provide conversions and additional methods. To achieve this we have to map the states of MultiEnum to single Enum. The connection is represented by `getSingleEnumClass` method on MultiEnum.

The easiest mapping is of course when both the representations are equal:

```php
<?php

class AbcEnum extends \Consistence\Enum\Enum
{

	const A = 1;
	const B = 2;
	const C = 4;

}

class AbcMultiEnum extends \Consistence\Enum\MultiEnum
{

	/**
	 * @return string
	 */
	public static function getSingleEnumClass()
	{
		return AbcEnum::class;
	}

}
```

> **Important:** Always remember that the values of MultiEnum must be powers of 2.

Usually this won't suit the situation and custom mapping will be preferred, use `convertSingleEnumValueToValue` and `convertValueToSingleEnumValue` which will be used to convert the values back and forth. In the next example `RolesEnum` is mapped to a single `RoleEnum`. Values 1, 2, 4 do not represent our domain so we won't polute the public API with these and we can start using only the natural string values.

```php
<?php

use Consistence\Type\ArrayType\ArrayType;

class RoleEnum extends \Consistence\Enum\Enum
{

	const USER = 'user';
	const EMPLOYEE = 'employee';
	const ADMIN = 'admin';

}

class RolesEnum extends \Consistence\Enum\MultiEnum
{

	/** @var integer[] format: single Enum value (string) => MultiEnum value (integer) */
	private static $singleMultiMap = [
		RoleEnum::USER => 1,
		RoleEnum::EMPLOYEE => 2,
		RoleEnum::ADMIN => 4,
	];

	/**
	 * @return string
	 */
	public static function getSingleEnumClass()
	{
		return RoleEnum::class;
	}

	/**
	 * Converts value representing a value from single Enum to MultiEnum counterpart
	 *
	 * @param string $singleEnumValue
	 * @return integer
	 */
	protected static function convertSingleEnumValueToValue($singleEnumValue)
	{
		return ArrayType::getValue(self::$singleMultiMap, $singleEnumValue);
	}

	/**
	 * Converts value representing a value from MultiEnum to single Enum counterpart
	 *
	 * @param integer $value
	 * @return string
	 */
	protected static function convertValueToSingleEnumValue($value)
	{
		return ArrayType::getKey(self::$singleMultiMap, $value);
	}

}
```

### Mapped enum conversions

If you have a mapped pair of enums you can use specialized conversion methods:

```php
<?php

$user = RoleEnum::get(RoleEnum::USER);
$employee = RoleEnum::get(RoleEnum::EMPLOYEE);
$admin = RoleEnum::get(RoleEnum::ADMIN);

$userAndAdmin = RolesEnum::getMultiByEnums([$user, $admin]); // create MultiEnum from multiple single Enums 
$userMultiEnum = RolesEnum::getMultiByEnum($user); // create MultiEnum from a single Enum

$userAndAdminRolesArray = $userAndAdmin->getEnums(); // [$user, $admin]
```

MultiEnum also implements `IteratorAggregate` so you can iterate trough single Enums with ease:

```php
<?php

$user = RoleEnum::get(RoleEnum::USER);
$admin = RoleEnum::get(RoleEnum::ADMIN);

$userAndAdmin = RolesEnum::getMultiByEnums([$user, $admin]);
foreach ($userAndAdmin as $role) {
	$role->getValue();
}
```

### Additional set operations

In addition to the [simple set operations](multi-enums.md#multienum-set-operations) (`containsValue` etc.) there is a also an enum version available:

* containsEnum
* addEnum
* removeEnum
* intersectEnum
* filter
