<?php

namespace Adagio\PropertyExposer;

trait PropertyExposerTrait
{
    /**
     *
     * @var array
     */
    private $_propertyExposerRegistry;

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($this->_getPropertyExposerRegistry()->isAvailable($name, 'write')) {
            $this->$name = $value;

            return;
        }

        throw new VisibilityException("Property '$name' is not writable.");
    }

    /**
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->_getPropertyExposerRegistry()->isAvailable($name, 'read')) {
            return $this->$name;
        }

        throw new VisibilityException("Property '$name' is not readable.");
    }

    /**
     *
     * @return PropertyRegistry
     */
    private function _getPropertyExposerRegistry(): PropertyRegistry
    {
        if (!$this->_propertyExposerRegistry) {
            $this->_propertyExposerRegistry = PropertyRegistry::forClass($this);
        }

        return $this->_propertyExposerRegistry;
    }
}
