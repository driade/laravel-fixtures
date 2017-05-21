# An easy and fast way to create controlled sets of Eloquent models for your tests or seeds, out of arrays

[![Latest Stable Version](https://poser.pugx.org/driade/laravel-fixtures/v/stable)](https://packagist.org/packages/driade/laravel-fixtures)
[![Latest Unstable Version](https://poser.pugx.org/driade/laravel-fixtures/v/unstable)](https://packagist.org/packages/driade/laravel-fixtures)
[![Build Status](https://travis-ci.org/driade/laravel-fixtures.svg?branch=master)](https://travis-ci.org/driade/laravel-fixtures)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/driade/laravel-fixtures/badges/quality-score.png?b=master&s=ae081333c0aa2e8edfb78c02e1db803e0bbb4ed3)](https://scrutinizer-ci.com/g/driade/laravel-fixtures/?branch=master)
[![License](https://poser.pugx.org/driade/laravel-fixtures/license)](https://packagist.org/packages/driade/laravel-fixtures)
[![Total Downloads](https://poser.pugx.org/driade/laravel-fixtures/downloads)](https://packagist.org/packages/driade/laravel-fixtures)

With this package you can define a set of data with arrays in PHP and get its representation as Eloquent models.

It's made for Laravel Illuminate\Database, so you can plug it in your Laravel projects and also in whichever project depending on Illuminate\Database, outside Laravel Framework.

Here it's and example definition:

```php
<?php

// fixtures/fixture1.php

namespace Driade\Fixtures\Test\Models;

return [
    User::class,
    'id'        => 1,
    'active'    => 1,
    'confirmed' => 1,
    'orders'    => [
        [
            Order::class,
            'id'        => 1,
            'total'     => 1,
            'shipped'   => 0,
        ],
        [
            Order::class,
            'id'        => 2,
            'total'     => 1,
            'shipped'   => 1,
        ],
    ],
];

```

you can load this definition in with

```php
$user = FixtureLoader::load(__DIR__ . '/fixtures/fixture1.php');
```

$user will have then (simplified)

```
Driade\Fixtures\Test\Models\User Object
(
    [attributes:protected] => Array
    (
        [id] => 1
        [active] => 1
        [confirmed] => 1
    )

    [relations:protected] => Array
    (
        [orders] => Illuminate\Database\Eloquent\Collection Object
        (
            [items:protected] => Array
            (
                [0] => Driade\Fixtures\Test\Models\Order Object
                (
                    [attributes:protected] => Array
                    (
                        [id] => 1
                        [user_id] => 1
                        [total] => 1
                        [shipped] => 0
                    )
                )

                [1] => Driade\Fixtures\Test\Models\Order Object
                (
                    [attributes:protected] => Array
                    (
                        [id] => 2
                        [user_id] => 1
                        [total] => 1
                        [shipped] => 1
                    )
                )
            )
        )
    )
)
```

## Installation

You can install the package via composer:

```bash
composer require driade/laravel-fixtures
```

## Usage

Just load the the fixture file and you'll have the object/s

```
$user = Driade\Fixtures\FixtureLoader::load(__DIR__ . '/fixtures/fixture1.php');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information of what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email david@mundosaparte.com instead of using the issue tracker.

## Credits

- [David Fernández] (https://github.com/driade) (https://twitter.com/davidfrafael)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
