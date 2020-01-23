<?php
declare(strict_types=1);

namespace Paket\Bero\Harness;

use Paket\Bero\Bero;
use Paket\Bero\Fixture\ClassImplementingInterface;
use Paket\Bero\Fixture\ClassInterface;
use PHPUnit\Framework\Assert;

trait AddInterfaceTestHarness
{
    abstract protected function getBero(): Bero;

    public function testGetObjectConstructsClassThatAddInterfaceRegistered()
    {
        $this->getBero()->addInterface(ClassInterface::class, ClassImplementingInterface::class);

        $object = $this->getBero()->getObject(ClassInterface::class);

        Assert::assertInstanceOf(ClassInterface::class, $object);
        Assert::assertInstanceOf(ClassImplementingInterface::class, $object);
    }

    public function testCallCallableConstructsClassThatAddInterfaceRegistered()
    {
        $this->getBero()->addInterface(ClassInterface::class, ClassImplementingInterface::class);

        $this->getBero()->callCallable(function (ClassInterface $object) {
            Assert::assertInstanceOf(ClassInterface::class, $object);
            Assert::assertInstanceOf(ClassImplementingInterface::class, $object);
        });
    }
}