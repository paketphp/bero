<?php
declare(strict_types=1);

namespace Paket\Bero\Container;

use Paket\Bero\Bero;
use Psr\Container\ContainerInterface;
use Throwable;

final class BeroContainer implements ContainerInterface
{
    /** @var Bero */
    private $bero;

    public function __construct(Bero $bero)
    {
        $this->bero = $bero;
    }

    public function get($id)
    {
        try {
            return $this->bero->getObject($id);
        } catch (Throwable $throwable) {
            throw new NotFoundException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }

    public function has($id): bool
    {
        try {
            $this->bero->getObject($id);
            return true;
        } catch (Throwable $throwable) {
            return false;
        }
    }
}