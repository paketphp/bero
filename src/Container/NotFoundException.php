<?php
declare(strict_types=1);

namespace Paket\Bero\Container;

use LogicException;
use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends LogicException implements NotFoundExceptionInterface
{
}