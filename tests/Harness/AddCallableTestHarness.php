<?php
declare(strict_types=1);

namespace Paket\Bero\Harness;

use Paket\Bero\Bero;
use Paket\Bero\Fixture\ClassImplementingInterface;
use Paket\Bero\Fixture\ClassInterface;
use Paket\Bero\Fixture\ClassWithoutConstructor;
use PHPUnit\Framework\Assert;

trait AddCallableTestHarness
{
    abstract protected function getBero(): Bero;

    public function testGetObjectCallsAddedCallable()
    {
        $existing = new ClassWithoutConstructor();
        $this->getBero()->addCallable(ClassWithoutConstructor::class, function () use ($existing) {
            return $existing;
        });

        $object = $this->getBero()->getObject(ClassWithoutConstructor::class);

        Assert::assertSame($existing, $object);
    }

    public function testCallCallableCallsAddedCallable()
    {
        $existing = new ClassWithoutConstructor();
        $this->getBero()->addCallable(ClassWithoutConstructor::class, function () use ($existing) {
            return $existing;
        });

        $this->getBero()->callCallable(function (ClassWithoutConstructor $object) use ($existing) {
            Assert::assertSame($existing, $object);
        });
    }

    public function testThatAddedObjectIsUsedForGetObjectForRegisteredInterface()
    {
        $existing = new ClassImplementingInterface();
        $this->getBero()->addObject(ClassImplementingInterface::class, $existing);
        $this->getBero()->addInterface(ClassInterface::class, ClassImplementingInterface::class);

        $object = $this->getBero()->getObject(ClassInterface::class);

        Assert::assertSame($existing, $object);
    }

    public function testThatAddedObjectIsUsedForCallCallableForRegisteredInterface()
    {
        $existing = new ClassImplementingInterface();
        $this->getBero()->addObject(ClassImplementingInterface::class, $existing);
        $this->getBero()->addInterface(ClassInterface::class, ClassImplementingInterface::class);

        $this->getBero()->callCallable(function (ClassInterface $object) use ($existing) {
            Assert::assertSame($existing, $object);
        });
    }
}