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


interface ServantAwareInterface
{
    /**
     * Accesses the servant. If a ServantInterface instance is provided, the given Servant will be chained.
     * Setting $inject to true will inject the Servant instead.
     *
     * @param ServantInterface|null $servant
     * @param bool $inject
     * @return ServantInterface
     */
    public function servant(ServantInterface $servant = null, bool $inject = false): ServantInterface;
}