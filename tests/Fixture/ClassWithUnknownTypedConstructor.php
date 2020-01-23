<?php
declare(strict_types=1);

namespace Paket\Bero\Fixture;

final class ClassWithUnknownTypedConstructor
{
    public function __construct(UnkownClass $c)
    {
    }
}