<?php
declare(strict_types=1);

namespace Paket\Bero\Container;

use LogicException;
use Paket\Bero\Bero;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;

final class BeroContainerTest extends TestCase
{
    public function testThatGetCallsBeroAndReturnsOnSuccess()
    {
        $id = __CLASS__;
        $return = new stdClass();
        $bero = $this->createMock(Bero::class);
        $bero->expects($this->once())
            ->method('getObject')
            ->with($id)
            ->willReturn($return);

        $container = new BeroContainer($bero);
        $this->assertSame($return, $container->get($id));
    }

    public function testThatGetCallsBeroAndThrowsOnFailure()
    {
        $id = __CLASS__;
        $bero = $this->createMock(Bero::class);
        $bero->expects($this->once())
            ->method('getObject')
            ->with($id)
            ->willThrowException(new LogicException());

        $container = new BeroContainer($bero);
        $this->expectException(NotFoundExceptionInterface::class);
        $container->get($id);
    }

    public function testThatHasCallsBeroAndReturnsTrueOnSuccess()
    {
        $id = __CLASS__;
        $return = new stdClass();
        $bero = $this->createMock(Bero::class);
        $bero->expects($this->once())
            ->method('getObject')
            ->with($id)
            ->willReturn($return);

        $container = new BeroContainer($bero);
        $this->assertTrue($container->has($id));
    }

    public function testThatHasCallsBeroAndReturnsFalseOnFailure()
    {
        $id = __CLASS__;
        $bero = $this->createMock(Bero::class);
        $bero->expects($this->once())
            ->method('getObject')
            ->with($id)
            ->willThrowException(new LogicException());

        $container = new BeroContainer($bero);
        $this->assertFalse($container->has($id));
    }
}