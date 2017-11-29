<?php

namespace Adagio\PropertyExposer;

use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlock;

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
        $properties = [];
        $className = is_object($class) ? get_class($class) : $class;
        $docComment = (new \ReflectionClass($className))->getDocComment();
        if ($docComment) {
            $docBlock = DocBlockFactory::createInstance()->create($docComment);

            $properties = self::forDocBLock($docBlock);
        }

        foreach ((new \ReflectionClass($className))->getTraitNames() as $traitClassName) {
            $properties = array_merge($properties, self::forClass($traitClassName)->properties);
        }

        return new self($properties);
    }

    static private function forDocBlock(DocBlock $docBlock): array
    {
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

        return $properties;
    }
}
