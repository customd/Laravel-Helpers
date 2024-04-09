<?php

namespace CustomD\LaravelHelpers\Traits;

use ReflectionClass;

trait CanCallProtectedMethods
{

    public static function setProtectedProperty($object, string $propertyName, $value)
    {
        $reflectionClass = new ReflectionClass($object);

        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
        $property->setAccessible(false);
    }

    public static function getProtectedProperty($object, string $propertyName): mixed
    {
        $reflectionClass = new ReflectionClass($object);

        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        $value = $property->getValue($object);
        $property->setAccessible(false);

        return $value;
    }

    public static function callProtectedMethod($object, string $methodName, ...$params)
    {
        $reflectionClass = new ReflectionClass($object);

        $method = $reflectionClass->getMethod($methodName);
        $method->setAccessible(true);
        $result = $method->invoke($object, ...$params);
        $method->setAccessible(false);
        return $result;
    }
}
