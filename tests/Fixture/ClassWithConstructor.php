<?php
declare(strict_types=1);

namespace Paket\Bero\Fixture;

class ClassWithConstructor
{
    /** @var ClassWithoutConstructor */
    public $first;
    /** @var ClassWithoutConstructor */
    public $second;
    /** @var ClassWithEmptyConstructor */
    public $third;

    public function __construct(
        ClassWithoutConstructor $first,
        ClassWithoutConstructor $second,
        ClassWithEmptyConstructor $third)
    {
        $this->first = $first;
        $this->second = $second;
        $this->third = $third;
    }
}