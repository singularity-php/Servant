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


class ServantDeflector implements ServantDeflectorInterface
{
    use ServantAwarenessTrait;

    protected $servant;

    public function __construct(ServantInterface $servant)
    {
        $this->servant = $servant;
    }

    /**
     * resolves the provided Reflection.
     *
     * @param \ReflectionFunctionAbstract $method
     * @return \Generator
     */
    public function resolve(\ReflectionFunctionAbstract $method): \Generator
    {
        foreach ( $method->getParameters() as $current ) {
            yield $this->servant->resolve($current);
        }
    }
}