<?php
/**
 * This file is part of the Singularity PHP Components.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Singularity\Servant\Tests;


use PHPUnit\Framework\TestCase;
use Singularity\Servant\BoxedServant;
use Singularity\Servant\BoxedServantInterface;
use Singularity\Servant\DefaultServantBox;
use Singularity\Servant\Servants\BlindServant;

class ServantBoxTest extends TestCase
{
    public function testFactory()
    {
        $this->assertInstanceof(
            BoxedServantInterface::class,
            DefaultServantBox::fromCallable($closure = function($a, $b, $c) {})
        );

        $this->assertInstanceOf(
            BoxedServantInterface::class,
            DefaultServantBox::fromClass(DefaultServantBox::class, '__construct')
        );

        $this->assertInstanceof(
            BoxedServantInterface::class,
            DefaultServantBox::fromClosure($closure)
        );

        $blindServant = new BlindServant();

        $this->assertInstanceof(
            BoxedServantInterface::class,
            BoxedServant::fromCallable($closure, $blindServant)
        );

        $this->assertInstanceof(
            BoxedServantInterface::class,
            BoxedServant::fromClass(DefaultServantBox::class, '__construct', $blindServant)
        );

        $this->assertInstanceof(
            BoxedServantInterface::class,
            BoxedServant::fromClosure($closure, $blindServant)
        );
    }
}