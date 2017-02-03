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


/**
 * Class BoxedServant
 *
 * @package Singularity\Servant
 */
class BoxedServant implements BoxedServantInterface
{
    use ServantBoxTrait;

    /**
     * BoxedServant constructor.
     *
     * @param \ReflectionFunctionAbstract $method
     * @param ServantInterface $servant
     */
    public function __construct(\ReflectionFunctionAbstract $method, ServantInterface $servant)
    {
        $this->servant = $servant;
        $this->method = $method;
    }

    /**
     * factory method for callable.
     *
     * @param callable $callback
     * @param ServantInterface $servant
     * @return BoxedServantInterface
     */
    public static function fromCallable(callable $callback, ServantInterface $servant): BoxedServantInterface
    {
        return new static(new \ReflectionFunction(\Closure::fromCallable($callback)), $servant);
    }

    /**
     * factory method for class-method pairs.
     *
     * @param string $class
     * @param string $method
     * @param ServantInterface $servant
     * @return BoxedServantInterface
     */
    public static function fromClass(string $class, string $method, ServantInterface $servant): BoxedServantInterface
    {
        return new static(new \ReflectionMethod($class, $method), $servant);
    }

    /**
     * factory method for closures.
     *
     * @param \Closure $closure
     * @param ServantInterface $servant
     * @return BoxedServantInterface
     */
    public static function fromClosure(\Closure $closure, ServantInterface $servant): BoxedServantInterface
    {
        return static::fromCallable($closure, $servant);
    }
}