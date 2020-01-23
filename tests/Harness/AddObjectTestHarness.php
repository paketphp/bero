<?php
declare(strict_types=1);

namespace Paket\Bero\Harness;

use Paket\Bero\Bero;
use Paket\Bero\Fixture\ClassWithoutConstructor;
use PHPUnit\Framework\Assert;

trait AddObjectTestHarness
{
    abstract protected function getBero(): Bero;

    public function testThatGetObjectGetsWhatAddObjectAdded()
    {
        $existing = new ClassWithoutConstructor();
        $this->getBero()->addObject(ClassWithoutConstructor::class, $existing);

        $object = $this->getBero()->getObject(ClassWithoutConstructor::class);

        Assert::assertSame($existing, $object);
    }

    public function testThatCallCallableGetsWhatAddObjectAdded()
    {
        $existing = new ClassWithoutConstructor();
        $this->getBero()->addObject(ClassWithoutConstructor::class, $existing);

        $this->getBero()->callCallable(function (ClassWithoutConstructor $object) use ($existing) {
            Assert::assertSame($existing, $object);
        });
    }
}