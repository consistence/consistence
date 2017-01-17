Enums
=====

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
```

The main advantages of using enums for representing set of values are:

* you can be sure, that the value is unchanged (not only validated once)
* you can use type hints to check that only the defined set of values is acceptable
* you can define behavior on top of the represented values

Comparing
---------

Once you have an enum instance, you can compare them in multiple ways:

```php
<?php

$red = CardColor::get(CardColor::RED);
$red2 = CardColor::get(CardColor::RED);
$black = CardColor::get(CardColor::BLACK);

// by instance
$red === $red; // true
$red === $red2; // true
$red === $black; // false

// with method
$red->equals($red); // true
$red->equals($red2); // true
$red->equals($black); // false

// by value
$red->equalsValue(CardColor::RED); // true
$red->equalsValue('red'); // true
$red->equalsValue(CardColor::BLACK); // false
```

Checking values
---------------

If you do not need an instance of the enum, but just need to validate a value, you can use `isValidValue` or `checkValue`:

```php
<?php

CardColor::isValidValue(CardColor::RED); // true
CardColor::isValidValue('red'); // true
CardColor::isValidValue('green'); // false

CardColor::checkValue(CardColor::RED); // ok
CardColor::checkValue('red'); // ok
CardColor::checkValue('green'); // Consistence\Enum\InvalidEnumValueException: green [string] is not a valid value, accepted values: black, red
```

Using enums as parameters
-------------------------

You can use type hints to check statically for the defined enum and always be sure you will not get invalid value:

```php
<?php

function doMagicTrick(CardColor $guessedCardColor)
{
	// ...
}
```

Defining behavior on top of values
----------------------------------

[OOP](https://en.wikipedia.org/wiki/Object-oriented_programming) is all about objects representing both data and their behavior. Enums with this implementation enable you just that:

```php
<?php

use Consistence\Type\ArrayType\ArrayType;

class CardSuit extends \Consistence\Enum\Enum
{

	const CLUBS = 'clubs';
	const DIAMONDS = 'diamonds';
	const HEARTS = 'hearts';
	const SPADES = 'spades';

	private static $reds = [
		self::DIAMONDS,
		self::HEARTS,
	];

	/**
	 * @return \CardColor
	 */
	public function getCardColor()
	{
		return CardColor::get(
			ArrayType::inArray($this->getValue(), self::$reds) ? CardColor::RED : CardColor::BLACK
		);
	}

}

$hearts = CardSuit::get(CardSuit::HEARTS);
```

This is extremely helpful, because when you get the data, you get also associated behavior with it and do not need to add separated dependencies.

```php
<?php

function doMagicTrick(CardSuit $guessedCardSuit)
{
	$color = $guessedCardSuit->getColor();
	if ($color->equalsValue(CardColor::RED)) {
		// ...
	}

	// ...
}
```

Ignored constant values
-----------------------

By default, values are read from constants defined on the enum class as shown in the `CardColor` and `CardSuit` examples above.

If you want to exclude some constants from being values (for example because they are needed for implementation of methods), you only need to list them in `getIgnoredConstantNames`:

```php
<?php

class CardSuit extends \Consistence\Enum\Enum
{

	const CLUBS = 'clubs';
	const DIAMONDS = 'diamonds';
	const HEARTS = 'hearts';
	const SPADES = 'spades';

	const SYMBOL_CLUBS = '♣';
	const SYMBOL_DIAMONDS = '♦';
	const SYMBOL_HEARTS = '♥';
	const SYMBOL_SPADES = '♠';

	/**
	 * @var string[] format: value(string) => symbol(string)
	 */
	private static $symbolMap = [
		self::CLUBS => self::SYMBOL_CLUBS,
		self::DIAMONDS => self::SYMBOL_DIAMONDS,
		self::HEARTS => self::SYMBOL_HEARTS,
		self::SPADES => self::SYMBOL_SPADES,
	];

	/**
	 * @return string
	 */
	public function getSymbol()
	{
		if (!isset(self::$symbolMap[$this->getValue()])) {
			throw new \Exception('Undefinded symbol');
		}

		return self::$symbolMap[$this->getValue()];
	}

	/**
	 * @return string[] names of constants which should not be used as valid values of this enum
	 */
	protected static function getIgnoredConstantNames()
	{
		return [
			'SYMBOL_CLUBS',
			'SYMBOL_DIAMONDS',
			'SYMBOL_HEARTS',
			'SYMBOL_SPADES',
		];
	}

}

// Consistence\Enum\InvalidEnumValueException: ♣ [string] is not a valid value, accepted values: clubs, diamonds, hearts, spades
CardSuit::get(CardSuit::SYMBOL_CLUBS);
```

Custom definition of values
---------------------------

If you want to define you set of values in other way than using constants, you can override the `getAvailableValues` method:

```php
<?php

class CardValue extends \Consistence\Enum\Enum
{

	/**
	 * @return string[]
	 */
	public static function getAvailableValues()
	{
		return array_merge([
			'A',
			'K',
			'Q',
			'J'
		], array_map('strval', range(2, 10)));
	}

}

$ace = CardValue::get('A');
```

Getting values
--------------

As mentioned above, enum instances are represented by their value, you can get this value with `getValue`. using this method outside of the enum itself (for the purpose of defining custom methods) or serialization (getting values for API/UI/persistence) should be limited. 

```php
<?php

$red = CardColor::get(CardColor::RED);

$value = $red->getValue(); // 'red'
```

Representing multiple values
----------------------------

If you want to represent multiple values from the same set in one instance (e.g.: enabled, available options etc.), check out [MultiEnum](multi-enums.md).

Integrations
------------

If you are using one of the following libraries/frameworks, check out these integrations:

* [Doctrine ORM integration](https://github.com/consistence/consistence-doctrine) provides integration to store Enums in database through entities, there is also [Symfony bundle with this integration](https://github.com/consistence/consistence-doctrine-symfony)
* [JMS Serializer integration](https://github.com/consistence/consistence-jms-serializer) provides integration to (de)serialize Enums, there is also [Symfony bundle with this integration](https://github.com/consistence/consistence-jms-serializer-symfony)
