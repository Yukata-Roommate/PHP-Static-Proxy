<?php

namespace YukataRm\StaticProxy;

/**
 * Static Proxy
 *
 * @package YukataRm\StaticProxy
 */
abstract class StaticProxy
{
    /**
     * get class name calling dynamic method
     *
     * @return string
     */
    abstract protected static function getCallableClassName(): string;

    /**
     * call dynamic method statically
     *
     * @param string $method
     * @param array<mixed> $parameters
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters): mixed
    {
        $className = static::getCallableClassName();

        if (!class_exists($className)) throw new \RuntimeException("class {$className} does not exist");

        $instance = new $className();

        if (!method_exists($instance, $method)) throw new \RuntimeException("method {$method} does not exist on class {$className}");

        $callableMethods = static::callableMethods();

        if (!empty($callableMethods) && !in_array($method, $callableMethods)) throw new \RuntimeException("method {$method} can not call");

        $uncallableMethods = static::uncallableMethods();

        if (!empty($uncallableMethods) && in_array($method, $uncallableMethods)) throw new \RuntimeException("method {$method} can not call");

        return $instance->$method(...$parameters);
    }

    /**
     * get callable methods
     *
     * @return array<string>
     */
    protected static function callableMethods(): array
    {
        return [];
    }

    /**
     * get uncallable methods
     *
     * @return array<string>
     */
    protected static function uncallableMethods(): array
    {
        return [];
    }
}
