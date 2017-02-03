<?php
/**
 * This file is part of the Singularity PHP Components.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Singularity\Servant;


use Singularity\Servant\Servants\BlindServant;

/**
 * Class DefaultServantBox
 *
 * @package Singularity\Servant
 */
class DefaultServantBox implements BoxedServantInterface
{
    use ServantBoxTrait;

    /**
     * DefaultServantBox constructor.
     *
     * @param \ReflectionFunctionAbstract $method
     */
    public function __construct(\ReflectionFunctionAbstract $method)
    {
        $this->method = $method;
        $this->servant = new BlindServant();
    }

    /**
     * factory method for callable.
     *
     * @param callable $callback
     * @return BoxedServantInterface
     */
    public static function fromCallable(callable $callback): BoxedServantInterface
    {
        return new static(new \ReflectionFunction(\Closure::fromCallable($callback)));
    }

    /**
     * factory method for class-method pairs.
     *
     * @param string $class
     * @param string $method
     * @return BoxedServantInterface
     */
    public static function fromClass(string $class, string $method): BoxedServantInterface
    {
        return new static(new \ReflectionMethod($class, $method));
    }

    /**
     * factory method for closures.
     *
     * @param \Closure $closure
     * @return BoxedServantInterface
     */
    public static function fromClosure(\Closure $closure): BoxedServantInterface
    {
        return static::fromCallable($closure);
    }
}