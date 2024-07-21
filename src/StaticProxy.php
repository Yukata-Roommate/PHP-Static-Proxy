<?php

namespace YukataRm\StaticProxy;

/**
 * Calling dynamic methods statically
 * 
 * @package YukataRm\StaticProxy
 */
abstract class StaticProxy
{
    /**
     * Get class name of call dynamic method
     * 
     * @return string
     */
    abstract protected static function getCallableClassName(): string;

    /**
     * Call dynamic method statically
     * 
     * @param string $method
     * @param array<mixed> $parameters
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters): mixed
    {
        // get class name
        $className = static::getCallableClassName();

        // if class does not exist, throw exception
        if (!class_exists($className)) throw new \RuntimeException("Class {$className} does not exist");

        // get instance of class
        $instance = new $className();

        // if method does not exist, throw exception
        if (!method_exists($instance, $method)) throw new \RuntimeException("Method {$method} does not exist on class {$className}");

        // get static callable methods
        $staticCallableMethods = static::staticCallableMethods();

        // if method can not call, throw exception
        if (!empty($staticCallableMethods) && !in_array($method, $staticCallableMethods)) throw new \RuntimeException("Method {$method} can not call");

        // get static uncallable methods
        $staticUncallableMethods = static::staticUncallableMethods();

        // if method can not call, throw exception
        if (!empty($staticUncallableMethods) && in_array($method, $staticUncallableMethods)) throw new \RuntimeException("Method {$method} can not call");

        // call dynamic method
        return $instance->$method(...$parameters);
    }

    /**
     * Get static callable methods
     * 
     * @return array<string>
     */
    protected static function staticCallableMethods(): array
    {
        return [];
    }

    /**
     * Get static uncallable methods
     * 
     * @return array<string>
     */
    protected static function staticUncallableMethods(): array
    {
        return [];
    }
}
