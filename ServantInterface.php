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


use Singularity\Servant\Exception\ServantException;

/**
 * Interface ServantInterface
 *
 * @package Singularity\Servant
 */
interface ServantInterface
{
    /**
     * Adds an ServantInterface instance to the end of the chain.
     *
     * @param ServantInterface $servant
     * @return ServantInterface
     */
    public function chain(ServantInterface $servant): ServantInterface;

    /**
     * Injects an ServantInterface after the called Servant, chains previously assigned Servants to the newly assigned.
     *
     * @param ServantInterface $servant
     * @return ServantInterface
     */
    public function inject(ServantInterface $servant): ServantInterface;

    /**
     * Returns the next Servant.
     *
     * @throws ServantException if no following Servant is present.
     * @return ServantInterface
     */
    public function next(): ServantInterface;

    /**
     * checks whether a following Servant is given or not.
     *
     * @return bool
     */
    public function hasNext(): bool;

    /**
     * Returns the last Servant. If no Servant is in the chain, the current Servant will be returned.
     *
     * @return ServantInterface
     */
    public function last(): ServantInterface;

    /**
     * Resolves the given Reflection from the current Servant chain position.
     *
     * @param \ReflectionParameter $parameter
     * @return mixed
     */
    public function resolve(\ReflectionParameter $parameter);
}