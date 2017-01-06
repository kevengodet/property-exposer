<?php

namespace Adagio\PropertyExposer;

use phpDocumentor\Reflection\DocBlockFactory;

final class PropertyRegistry
{
    const READ  = 'read';
    const WRITE = 'write';

    private $properties = [];

    /**
     *
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    /**
     *
     * @param string $propertyName
     * @param string $type
     *
     * @return bool
     */
    public function isAvailable(string $propertyName, string $type = self::READ): bool
    {
        return isset($this->properties[$propertyName][$type]);
    }

    /**
     *
     * @param string|object $class Class name or class instance
     *
     * @return PropertyRegistry
     */
    static public function forClass($class): PropertyRegistry
    {
        $className = is_object($class) ? get_class($class) : $class;

        $docBlock = DocBlockFactory::createInstance()
                    ->create((new \ReflectionClass($className))->getDocComment());

        $properties = [];

        foreach ($docBlock->getTagsByName('property') as $tag) {
            $properties[$tag->getVariableName()]['read'] = true;
            $properties[$tag->getVariableName()]['write'] = true;
        }

        foreach ($docBlock->getTagsByName('property-read') as $tag) {
            $properties[$tag->getVariableName()]['read'] = true;
        }

        foreach ($docBlock->getTagsByName('property-write') as $tag) {
            $properties[$tag->getVariableName()]['write'] = true;
        }

        return new self($properties);
    }
}
