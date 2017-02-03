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


use Singularity\Servant\Exception\NotResolvableException;
use Singularity\Servant\Exception\ServantException;

/**
 * Trait ServantTrait
 *
 * @package Singularity\Servant
 */
trait ServantTrait
{
    /**
     * holds the next Servant.
     *
     * @var ServantInterface|null
     */
    protected $nextServant;

    /**
     * Adds an ServantInterface instance to the end of the chain.
     *
     * @param ServantInterface $servant
     * @return ServantInterface
     */
    final public function chain(ServantInterface $servant): ServantInterface
    {
        if ( $this->nextServant instanceof ServantInterface ) {
            $this->last()->chain($servant);

            /** @var ServantInterface $this */
            return $this;
        }

        $this->nextServant = $servant;

        return $this;
    }

    /**
     * Injects an ServantInterface after the called Servant, chains previously assigned Servants to the newly assigned.
     *
     * @param ServantInterface $servant
     * @return ServantInterface
     */
    final public function inject(ServantInterface $servant): ServantInterface
    {
        if ( $this->nextServant instanceof ServantInterface ) {
            $servant->inject($this->nextServant);
        }

        $this->nextServant = $servant;

        /** @var ServantInterface $this */
        return $this;
    }

    /**
     * Returns the next Servant.
     *
     * @throws ServantException if no following Servant is present.
     * @return ServantInterface
     */
    final public function next(): ServantInterface
    {
        if ( ! $this->nextServant instanceof ServantInterface ) {
            throw new ServantException('No following servant');
        }

        return $this->nextServant;
    }

    /**
     * checks whether a following Servant is given or not.
     *
     * @return bool
     */
    final public function hasNext(): bool
    {
        return $this->nextServant instanceof ServantInterface;
    }

    /**
     * Returns the last Servant. If no Servant is in the chain, the current Servant will be returned.
     *
     * @return ServantInterface
     */
    final public function last(): ServantInterface
    {
        $current = $this;

        while ( $current->hasNext() ) {
            $current = $current->next();
        }

        /** @var ServantInterface $current */
        return $current;
    }

    /**
     * proceeds with the next servant.
     *
     * @param \ReflectionParameter $parameter
     * @return mixed
     */
    final protected function proceed(\ReflectionParameter $parameter)
    {
        if ( ! $this->hasNext() ) {
            throw new NotResolvableException('`$'.$parameter->getName().'` dependency not resolvable');
        }

        return $this->next()->resolve($parameter);
    }
}