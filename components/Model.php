<?php


namespace app\components;


use ReflectionClass;
use ReflectionProperty;

class Model {
    /**
     * Model constructor.
     * @param array $values
     * @throws \ReflectionException
     */
    public function __construct($values = []) {
        $class = new ReflectionClass($this);

        foreach ($values as $propertyToSet => $value) {
            $property = $class->getProperty($propertyToSet);

            if ($property instanceof ReflectionProperty) {
                $property->setValue($this, $value);
            }
        }
    }
}