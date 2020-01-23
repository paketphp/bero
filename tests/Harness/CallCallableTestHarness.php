<?php
declare(strict_types=1);

namespace Paket\Bero\Harness;

require_once __DIR__ . '/../Fixture/callCallableTestFunction.php';

use Paket\Bero\Bero;
use Paket\Bero\Fixture\ClassWithCallable;
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

    public function testThatMethodCallableWorks()
    {
        $this->getBero()->callCallable([new ClassWithCallable(), 'methodCallable']);
        Assert::assertInstanceOf(ClassWithoutConstructor::class, ClassWithCallable::$c);
    }

    public function testThatStaticCallableWorks()
    {
        $this->getBero()->callCallable([ClassWithCallable::class, 'staticCallable']);
        Assert::assertInstanceOf(ClassWithoutConstructor::class, ClassWithCallable::$c);
    }

    public function testThatFunctionCallableWorks()
    {
        global $callCallableTestFunctionResult;
        $this->getBero()->callCallable('Paket\Bero\Fixture\callCallableTestFunction');
        Assert::assertInstanceOf(ClassWithoutConstructor::class, $callCallableTestFunctionResult);
    }

    public function testThatCallCallableReturnsCorrectValue()
    {
        $value = $this->getBero()->callCallable(function () {
            return 17;
        });

        Assert::assertSame(17, $value);
    }
}