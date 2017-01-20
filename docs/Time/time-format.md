Time Format
===========

Using [value objects](http://martinfowler.com/bliki/ValueObject.html) is great, because you get clear type hint and you can be sure, that the value you get is in consistent state and that it is valid (if the representation follows the principles).

[`DateTime`](http://php.net/manual/en/class.datetime.php) is one of the few value objects which are natively in PHP. Unfortunately, PHP again prefers using magic behavior over clear errors by default and there is also quite a lot of commonly required functionality missing, so Consistence tries to help with these shortcomings.

Strict time format
------------------

PHP tries to construct an instance even if the format does not match or the given date is a nonsense and in many cases it succeeds. Of course, this can be helpful sometimes, but leads to unpredictable results.

These are examples of situations when the value does not match specified format, but PHP will still construct [`DateTime`](http://php.net/manual/en/class.datetime.php) instance:

```php
<?php

// H requires leading zero, but there is none
var_dump(DateTime::createFromFormat('H:i', '2:30'));

// m and d require leading zeroes, but here are none
var_dump(DateTime::createFromFormat('Y-m-d', '2016-1-2'));

// P in timezone should contain semicolon, but there is none in the value
var_dump(DateTime::createFromFormat('Y-m-d\TH:i:sP', '2016-03-21T14:30:32+0130'));
```

Following strict format is usually important in an API or a parser, or anywhere where validation is required. If inputs not matching specified formats can go through, they will probably cause some problems later.

These are examples of values which are parsed even though they represent non-existing dates:

```php
<?php

// there is no 25th hour in the day
var_dump(DateTime::createFromFormat('H:i', '25:00'));

// this day does not exist when not in leap year
var_dump(DateTime::createFromFormat('Y-m-d', '2015-02-29'));

// this time does not exist, the time is moving to DST, skipping from 2:00 to 3:00
var_dump(DateTime::createFromFormat('Y-m-d H:i:s e', '2016-03-27 02:30:00 Europe/Prague'));
```

In these situations PHP will "overflow" the date into the future and create a valid, but different date than the one which was given. This is even more serious because when user inputs time, he cannot expect that a completely different time will be used without issuing a warning.

In practice magic behavior usually will do more harm than good, so I recommend using strict behavior offered by Consistence:

```php
<?php

use Consistence\Time\TimeFormat;

// example of strict validation of format:

// \Consistence\Time\TimeDoesNotMatchFormatException: Time "2:30" does not match format "H:i"
TimeFormat::createDateTimeFromFormat('H:i', '2:30');

// example of strict validation of values:

// \Consistence\Time\TimeDoesNotExistException: Time given in "25:00" does not exist
TimeFormat::createDateTimeFromFormat('H:i', '25:00');
```

Of course, there is also `TimeFormat::createDateTimeImmutableFromFormat()` for creating [`DateTimeImmutable`](http://php.net/manual/en/class.datetimeimmutable.php) instances which I think should be preferred.

 If you do not need an instance, only validation, you can use `TimeFormat::checkTime()` or  `TimeFormat::isValidTime()`.

DateTime to Unix timestamp conversion
-------------------------------------

There are a lot of applications where you have to deal with timestamps. Although I definitely recommend using [`DateTime`](http://php.net/manual/en/class.datetime.php) (or other value objects) rather than timestamps, it might be that they are stored in the database, coming from an API/import or being used by a library or some part of the application itself. This is especially case of legacy applications/code.

In PHP, there is no convenient way to convert timestamps to [`DateTime`](http://php.net/manual/en/class.datetime.php)s. There are two documented ways:

```php
<?php

$timestamp = 1483822631;

var_dump(DateTime::createFromFormat('U', $timestamp));
var_dump(new DateTime('@' . $timestamp));

/*

object(DateTime)#2 (3) {
	["date"]=> string(26) "2017-01-07 20:57:11.000000"
	["timezone_type"]=> int(1)
	["timezone"]=> string(6) "+00:00"
}

*/
```

Problem with both these approaches is that neither of them is particularly expressive and the instance is created with `+00:00` timezone, which is probably different offset and timezone type from the system one, which can cause weird situations. Both the [`DateTime`](http://php.net/manual/en/class.datetime.php) constructor and the `createFromFormat` accept a timezone parameter, but this parameter is ignored in combination with timezone value and no error is given, so this is again very easy to miss. Consistence on the other hand creates [`DateTime`](http://php.net/manual/en/class.datetime.php) or ([`DateTimeImmutable`](http://php.net/manual/en/class.datetimeimmutable.php)) with the system timezone:

```php
<?php

use Consistence\Time\TimeFormat;

$timestamp = 1483822631;

var_dump(TimeFormat::createDateTimeFromTimestamp($timestamp));
var_dump(TimeFormat::createDateTimeImmutableFromTimestamp($timestamp));

/*

object(DateTime)#2 (3) {
	["date"]=> string(26) "2017-01-07 21:57:11.000000"
	["timezone_type"]=> int(3)
	["timezone"]=> string(16) "Europe/Prague"
}
object(DateTimeImmutable)#2 (3) {
	["date"]=> string(26) "2017-01-07 21:57:11.000000"
	["timezone_type"]=> int(3)
	["timezone"]=> string(16) "Europe/Prague"
}

*/
```

Both methods accept a second parameter, where you can specify a custom timezone of the created instance.

DateTime and DateTimeImmutable conversion
-----------------------------------------

[`DateTimeImmutable`](http://php.net/manual/en/class.datetimeimmutable.php), which was introduced in PHP 5.5 helps to build more predictable applications by restricting changes to existing instances. Using immutable objects is much safer, because you cannot modify them by accident, or they cannot be modified by a method, where you send the value as a parameter. For these reasons, I think you should use [`DateTimeImmutable`](http://php.net/manual/en/class.datetimeimmutable.php) by default.

Unfortunately, there are a lot of applications or libraries working only with [`DateTime`](http://php.net/manual/en/class.datetime.php) (not [`DateTimeInterface`](http://php.net/manual/en/class.datetimeinterface.php) which would enable you to use both) and so conversion between [`DateTime`](http://php.net/manual/en/class.datetime.php) and [`DateTimeImmutable`](http://php.net/manual/en/class.datetimeimmutable.php) is needed. PHP does not offer a comfortable way to do this, but Consistence does:

```php
<?php

use Consistence\Time\TimeFormat;

$dateTime = TimeFormat::createDateTimeFromDateTimeInterface(new DateTimeImmutable());
$dateTimeImmutable = TimeFormat::createDateTimeImmutableFromDateTimeInterface(new DateTime());
```

Time constants
--------------

```php
<?php

$dateTime = new DateTime();
var_dump($dateTime->format('z'));
```

When you look at the above snippet of code, do you know exactly what `z` means without looking it up somewhere? And does every consumer of your code know that?

Leaving undescriptive scalar values in code is commonly a bad practice decreasing readability. It can also be more difficult for writing as well, because you have to actually remember the correct letter for what you need and there is nothing to guide you.

This can be remedied by introducing descriptive list of constants. Consistence provides such a list in [`TimeFormat`](/src/Time/TimeFormat.php), which allows you to make the code more readable:

```php
<?php

use Consistence\Time\TimeFormat;

$dateTime = new DateTime();
var_dump($dateTime->format(TimeFormat::DAY_OF_YEAR));
```

This should be much more readable for anyone who stumbles upon it. Also when writing code you can type `TimeFormat::` and use autocomplete in your IDE to search for what exactly you need.

Go checkout the full list of constants in [`TimeFormat`](/src/Time/TimeFormat.php).
