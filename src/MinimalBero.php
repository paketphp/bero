<?php
declare(strict_types=1);

namespace Paket\Bero;

use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;

final class MinimalBero implements Bero
{
    private $objects = [];

    private $callables = [];

    /**
     * Adds callable to be called when instantiating class
     *
     * @param string $class
     * @param callable $callable
     */
    public function addCallable(string $class, callable $callable): void
    {
        $this->callables[$class] = $callable;
    }

    /**
     * Maps interface to a class implementation
     *
     * @param string $interface
     * @param string $class
     */
    public function addInterface(string $interface, string $class): void
    {
        $this->callables[$interface] = function () use ($class) {
            return $this->getObject($class);
        };
    }

    /**
     * Adds instance object for a class
     *
     * @param string $class
     * @param object $object
     */
    public function addObject(string $class, object $object): void
    {
        $this->objects[$class] = $object;
    }

    /**
     * Calls callable by expanding its parameters & returning the result
     *
     * @param callable $callable
     * @return mixed
     * @throws ReflectionException
     */
    public function callCallable(callable $callable)
    {
        $rf = is_array($callable) ?
            new ReflectionMethod($callable[0], $callable[1]) :
            new ReflectionFunction($callable);
        return $callable(...$this->expandParameters($rf->getParameters()));
    }

    /**
     * Gets instance object for a class
     *
     * @param string $class
     * @return object
     * @throws ReflectionException
     */
    public function getObject(string $class): object
    {
        if (isset($this->objects[$class])) {
            return $this->objects[$class];
        }

        if (isset($this->callables[$class])) {
            $object = $this->callCallable($this->callables[$class]);
        } else {
            $object = $this->instantiateClass($class);
        }
        return $this->objects[$class] = $object;
    }

    /**
     * Instantiates a class by expanding it's constructor & returns the resulting instance object
     *
     * @param string $class
     * @return object
     * @throws ReflectionException
     */
    private function instantiateClass(string $class): object
    {
        $rc = new ReflectionClass($class);
        $rm = $rc->getConstructor();
        if ($rm) {
            $parameters = $this->expandParameters($rm->getParameters());
            return $rc->newInstanceArgs($parameters);
        }
        return $rc->newInstance();
    }

    /**
     * Expands function parameters & returns their instance objects
     *
     * @param ReflectionParameter[] $rps
     * @return object[]
     * @throws ReflectionException
     */
    private function expandParameters(array $rps): array
    {
        $parameters = [];
        foreach ($rps as $rp) {
            $parameters[] = $this->getObject($rp->getType()->getName());
        }
        return $parameters;
    }
}