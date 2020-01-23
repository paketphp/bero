<?php
declare(strict_types=1);

namespace Paket\Bero\Harness;

use Paket\Bero\Bero;
use Paket\Bero\Fixture\ClassWithConstructor;
use Paket\Bero\Fixture\ClassWithEmptyConstructor;
use Paket\Bero\Fixture\ClassWithoutConstructor;
use PHPUnit\Framework\Assert;

trait GetObjectTestHarness
{
    abstract protected function getBero(): Bero;

    public function testThatGetObjectCanConstructClassWithoutConstructor()
    {
        $object = $this->getBero()->getObject(ClassWithoutConstructor::class);
        Assert::assertInstanceOf(ClassWithoutConstructor::class, $object);
    }

    public function testThatGetObjectCanConstructClassWithEmptyConstructor()
    {
        $object = $this->getBero()->getObject(ClassWithEmptyConstructor::class);
        Assert::assertInstanceOf(ClassWithEmptyConstructor::class, $object);
    }

    public function testThatGetObjectCanConstructClassWithConstructor()
    {
        /** @var ClassWithConstructor $object */
        $object = $this->getBero()->getObject(ClassWithConstructor::class);
        Assert::assertInstanceOf(ClassWithConstructor::class, $object);
        Assert::assertInstanceOf(ClassWithoutConstructor::class, $object->first);
        Assert::assertInstanceOf(ClassWithoutConstructor::class, $object->second);
        Assert::assertInstanceOf(ClassWithEmptyConstructor::class, $object->third);
        Assert::assertSame($object->first, $object->second);
    }
}