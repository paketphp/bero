<?php
declare(strict_types=1);

namespace Paket\Bero;

use LogicException;
use Paket\Bero\Fixture\ClassInterface;
use Paket\Bero\Fixture\ClassWithoutTypedConstructor;
use Paket\Bero\Fixture\ClassWithScalarConstructor;
use Paket\Bero\Fixture\ClassWithUnionConstructor;
use Paket\Bero\Fixture\ClassWithUnknownTypedConstructor;
use Paket\Bero\Harness\AddCallableTestHarness;
use Paket\Bero\Harness\AddInterfaceTestHarness;
use Paket\Bero\Harness\AddObjectTestHarness;
use Paket\Bero\Harness\CallCallableTestHarness;
use Paket\Bero\Harness\GetObjectTestHarness;
use PHPUnit\Framework\TestCase;

final class StrictBeroTest extends TestCase
{
    use AddCallableTestHarness;
    use AddInterfaceTestHarness;
    use AddObjectTestHarness;
    use CallCallableTestHarness;
    use GetObjectTestHarness;

    /** @var Bero */
    private $bero;

    protected function setUp(): void
    {
        $this->bero = new StrictBero();
    }

    protected function getBero(): Bero
    {
        return $this->bero;
    }

    public function testThatGetObjectThrowsOnClassWithoutTypeConstructor()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->getObject(ClassWithoutTypedConstructor::class);
    }

    public function testThatGetObjectThrowsOnClassWithScalarConstructor()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->getObject(ClassWithScalarConstructor::class);
    }

    public function testThatGetObjectThrowsOnClassWithUnknownTypedConstructor()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->getObject(ClassWithUnknownTypedConstructor::class);
    }

    public function testThatGetObjectThrowsOnUnknownClassName()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->getObject('UnknownClass');
    }

    public function testThatCallCallableThrowsOnClassWithoutTypeConstructor()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->callCallable(function (ClassWithoutTypedConstructor $object) {
        });
    }

    public function testThatCallCallableThrowsOnClassWithScalarConstructor()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->callCallable(function (ClassWithScalarConstructor $object) {
        });
    }

    public function testGetObjectThrowsOnNonRegisteredInterface()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->getObject(ClassInterface::class);
    }

    public function testCallCallableThrowsOnNonRegisteredInterface()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->callCallable(function (ClassInterface $object) {
        });
    }

    public function testThatCallCallableThrowsOnClassWithUnknownTypedConstructor()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->callCallable(function (ClassWithUnknownTypedConstructor $object) {
        });
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testThatGetObjectThrowsOnUnionTypes()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->getObject(ClassWithUnionConstructor::class);
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testThatCallCallableThrowsOnUnionTypes()
    {
        $this->expectException(LogicException::class);
        $this->getBero()->callCallable(function (ClassWithUnionConstructor $object) {
        });
    }
}