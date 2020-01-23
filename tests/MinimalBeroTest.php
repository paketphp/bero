<?php
declare(strict_types=1);

namespace Paket\Bero;

use Paket\Bero\Harness\AddCallableTestHarness;
use Paket\Bero\Harness\AddInterfaceTestHarness;
use Paket\Bero\Harness\AddObjectTestHarness;
use Paket\Bero\Harness\CallCallableTestHarness;
use Paket\Bero\Harness\GetObjectTestHarness;
use PHPUnit\Framework\TestCase;

final class MinimalBeroTest extends TestCase
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
        $this->bero = new MinimalBero();
    }

    protected function getBero(): Bero
    {
        return $this->bero;
    }
}