# adagio/property-exposer

`adagio/property-exposer` gives access to private properties based on phpDoc directives.

The goal is to avoid getters and setters in the class body and keep only meaningful
action methods:

```php
<?php

// Before

class Foo
{
    private $bar, $baz;

    public function setBar($value)
    {
        $this->bar = $value;
    }

    public function getBar()
    {
        return $this->bar;
    }

    public function getBaz()
    {
        return $this->baz;
    }

    public function doSomething()
    {
        // ...
    }
}

// After

/**
 *
 * @property int $bar Bar
 * @property-read string $baz Baz
 */
class Foo
{
    use PropertyExposerTrait;

    private $bar, $baz;

    public function doSomething()
    {
        // ...
    }
}

```

Domain methods appears immediatly, easying code understanding and maintenance.

One consequence is that DTO objects have no more methods, they appears for what
their are: values transporters...


## Installation

The recommended way to install `adagio/property-exposer` is through Composer:

```shell
composer require adagio/property-exposer
```

After installing, you need to require Composer's autoloader.


## Usage

```php
<?php

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
