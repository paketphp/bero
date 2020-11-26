<?php
declare(strict_types=1);

namespace Paket\Bero\Fixture;

class ClassWithUnionConstructor
{
    /** @var ClassWithoutConstructor|ClassWithEmptyConstructor */
    public $class;

    public function __construct(ClassWithoutConstructor|ClassWithEmptyConstructor $class)
    {
        $this->$class = $class;
    }
}