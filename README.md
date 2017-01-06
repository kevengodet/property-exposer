# adagio/property-exposer

`adagio/property-exposer` gives access to private properties based on phpDoc directives.

It replaces accessors and mutators for easy property access.

## Installation

The recommended way to install `adagio/property-exposer` is through Composer:

```shell
composer require adagio/property-exposer
```

After installing, you need to require Composer's autoloader.


## Usage

```php
<?php

require 'vendor/autoload.php';

use Adagio\PropertyExposer\PropertyExposerTrait;

/**
 *
 * @property int $bar Bar
 * @property-read string $baz Baz
 */
final class Foo
{
    use PropertyExposerTrait;

    /**
     *
     * @var int
     */
    private $bar = 7;

    /**
     *
     * @var string
     */
    private $baz = 'test';
}

$foo = new Foo;

echo $foo->bar."\n"; // 7
$foo->bar = 1;
echo $foo->bar."\n"; // 1

echo $foo->baz."\n"; // "test"
$foo->baz = 'test2'; // Exception...
echo $foo->baz."\n";
```
