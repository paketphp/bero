<?php
declare(strict_types=1);

namespace Paket\Bero\Fixture;

function callCallableTestFunction(ClassWithoutConstructor $c)
{
    global $callCallableTestFunctionResult;
    $callCallableTestFunctionResult = $c;
}