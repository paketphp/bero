<?php
declare(strict_types=1);

namespace Paket\Bero\Fixture;

final class ClassWithoutTypedConstructor
{
    public function __construct($i)
    {
    }
}