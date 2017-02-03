<?php
/**
 * This file is part of the Singularity PHP Components.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Singularity\Servant\Servants;


use Singularity\Servant\Exception\NotResolvableException;
use Singularity\Servant\Exception\ServantException;
use Singularity\Servant\ServantInterface;
use Singularity\Servant\ServantTrait;

/**
 * Class InterfaceContainerServant
 *
 * @package Singularity\Servant
 */
class InterfaceContainerServant implements ServantInterface
{
    use ServantTrait;

    /**
     * holds all interface bindings.
     *
     * @var array
     */
    protected $items = [];

    /**
     * binds a object, class name or the interface name itself to the provided interface.
     *
     * @param string $interface
     * @param null $concrete
     * @return ServantInterface
     */
    public function bind(string $interface, $concrete = null): ServantInterface
    {
        if ( null === $concrete ) {
            $this->items[$interface] = function() use ($interface) {
                return new $interface;
            };
        }
        else if ( is_object($concrete) ) {
            if ( ! is_a($concrete, $interface) ) {
                throw new ServantException('Incompatible binding for interface: '.$interface);
            }

            $this->items[$interface] = function() use ($concrete) {
                return $concrete;
            };
        }
        else if ( is_string($concrete) ) {
            if ( ! is_a($concrete, $interface, true) ) {
                throw new ServantException('Incompatible binding for interface: '.$interface);
            }

            $this->items[$interface] = function() use ($concrete) {
                return new $concrete;
            };
        }
        else {
            throw new ServantException('Can not bind a concrete of type '.gettype($concrete).' to an interface');
        }

        return $this;
    }

    /**
     * binds a factory callable to the provided interface.
     *
     * @param string $interface
     * @param callable $factory
     * @return ServantInterface
     */
    public function factory(string $interface, callable $factory): ServantInterface
    {
        $factoryCallback = \Closure::fromCallable($factory);

        $this->items[$interface] = $factoryCallback;

        return $this;
    }

    /**
     * Resolves the given Reflection from the current Servant chain position.
     *
     * @param \ReflectionParameter $parameter
     * @return mixed
     */
    public function resolve(\ReflectionParameter $parameter)
    {
        if ( ! $parameter->getClass() || ! array_key_exists($parameter->getClass()->getName(), $this->items) ) {
            return $this->proceed($parameter);
        }

        return call_user_func(
            $this->items[$parameter->getClass()->getName()],
            ... $this->resolveClosure($this->items[$parameter->getClass()->getName()], $parameter)
        );
    }

    /**
     * resolves the dependencies of a given closure in context of its contextual reflection.
     *
     * @param \Closure $closure
     * @param \ReflectionParameter $origin
     * @return \Generator
     */
    protected function resolveClosure(\Closure $closure, \ReflectionParameter $origin): \Generator
    {
        $reflection = new \ReflectionFunction($closure);

        foreach ( $reflection->getParameters() as $parameter ) {
            if ( $parameter->getClass() && array_key_exists($parameter->getClass()->getName(), $this->items) ) {
                yield $this->resolve($parameter);

                continue;
            }

            if ( $parameter->isOptional() ) {
                yield $parameter->getDefaultValue();

                continue;
            }

            throw new NotResolvableException('Unresolvable Closure for parameter: '.$origin->getName());
        }
    }
}