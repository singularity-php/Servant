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


trait ServantBoxTrait
{
    use ServantAwarenessTrait;

    /**
     * holds the reflection of the box.
     *
     * @var \ReflectionFunctionAbstract
     */
    protected $method;

    /**
     * resolves the boxed servant to a generator of dependencies.
     *
     * @return \Generator
     */
    public function resolve(): \Generator
    {
        foreach ( $this->method->getParameters() as $current ) {
            yield $this->servant->resolve($current);
        }
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Generator An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator(): \Generator
    {
        return $this->resolve();
    }
}