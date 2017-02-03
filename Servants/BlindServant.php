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


use Singularity\Servant\ServantInterface;
use Singularity\Servant\ServantTrait;

/**
 * Class BlindServant
 *
 * @package Singularity\Servant
 */
class BlindServant implements ServantInterface
{
    use ServantTrait;

    /**
     * Resolves the given Reflection from the current Servant chain position.
     *
     * @param \ReflectionParameter $parameter
     * @return mixed
     */
    public function resolve(\ReflectionParameter $parameter)
    {
        if ( ! $parameter->isOptional() && $parameter->getClass() && class_exists($class = $parameter->getClass()->getName(), true) ) {
            return new $class;
        }

        if ( $parameter->isOptional() ) {
            return $parameter->getDefaultValue();
        }

        return $this->proceed($parameter);
    }
}