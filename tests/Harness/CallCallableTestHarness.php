<?php
declare(strict_types=1);

namespace Paket\Bero\Harness;

use Paket\Bero\Bero;
use Paket\Bero\Fixture\ClassWithConstructor;
use Paket\Bero\Fixture\ClassWithEmptyConstructor;
use Paket\Bero\Fixture\ClassWithoutConstructor;
use PHPUnit\Framework\Assert;

trait CallCallableTestHarness
{
    abstract protected function getBero(): Bero;

    public function testThatCallCallableCanConstructMultipleClasses()
    {
        $this->getBero()->callCallable(function (
            ClassWithoutConstructor $first,
            ClassWithoutConstructor $second,
            ClassWithEmptyConstructor $third,
            ClassWithConstructor $fourth) {

            Assert::assertInstanceOf(ClassWithoutConstructor::class, $first);
            Assert::assertInstanceOf(ClassWithoutConstructor::class, $second);
            Assert::assertInstanceOf(ClassWithEmptyConstructor::class, $third);
            Assert::assertInstanceOf(ClassWithConstructor::class, $fourth);
            Assert::assertSame($first, $second);
            Assert::assertSame($first, $fourth->first);
            Assert::assertSame($first, $fourth->second);
            Assert::assertSame($third, $fourth->third);
        });
    }

    public function testThatCallCallableReturnsCorrectValue()
    {
        $value = $this->getBero()->callCallable(function () {
            return 17;
        });

        Assert::assertSame(17, $value);
    }
}