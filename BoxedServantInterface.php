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
 * Interface BoxedServantInterface
 *
 * @package Singularity\Servant
 */
interface BoxedServantInterface extends \IteratorAggregate, ServantAwareInterface
{
    /**
     * resolves the boxed servant to a generator of dependencies.
     *
     * @return \Generator
     */
    public function resolve(): \Generator;
}