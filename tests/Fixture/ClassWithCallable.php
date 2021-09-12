<?php
declare(strict_types=1);

namespace Paket\Bero\Fixture;

final class ClassWithCallable
{
    public static $c;

    public function methodCallable(ClassWithoutConstructor $c): void
    {
        self::$c = $c;
    }

    public static function staticCallable(ClassWithoutConstructor $c): void
    {
        self::$c = $c;
    }

    public function __invoke(ClassWithoutConstructor $c): void
    {
        self::$c = $c;
    }
}