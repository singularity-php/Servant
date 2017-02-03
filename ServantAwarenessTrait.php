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


trait ServantAwarenessTrait
{
    /**
     * holds the servant.
     *
     * @var ServantInterface
     */
    protected $servant;

    /**
     * Accesses the servant. If a ServantInterface instance is provided, the given Servant will be chained.
     * Setting $inject to true will inject the Servant instead.
     *
     * @param ServantInterface|null $servant
     * @param bool $inject
     * @return ServantInterface
     */
    public function servant(ServantInterface $servant = null, bool $inject = false): ServantInterface
    {
        if ( $servant instanceof ServantInterface && $inject ) {
            $servant->inject($this->servant);
            $this->servant = $servant;
        }

        if ( $servant instanceof ServantInterface && ! $inject ) {
            $this->servant->chain($servant);
        }

        return $this->servant;
    }
}