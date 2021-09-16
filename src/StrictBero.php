<?php
declare(strict_types=1);

namespace Paket\Bero;

use Closure;
use LogicException;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

final class StrictBero implements Bero
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
     */
    public function callCallable(callable $callable)
    {
        try {
            $rf = new ReflectionFunction(Closure::fromCallable($callable));
        } catch (ReflectionException $e) {
            throw new LogicException('Failed reflecting callable', 0, $e);
        }
        return $callable(...$this->expandParameters($rf->getParameters()));
    }

    /**
     * Gets instance object for a class
     *
     * @param string $class
     * @return object
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
        $this->objects[$class] = $object;
        return $object;
    }

    /**
     * Instantiates a class by expanding it's constructor & returns the resulting instance object
     *
     * @param string $class
     * @return object
     */
    private function instantiateClass(string $class): object
    {
        try {
            $rc = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw new LogicException("Failed getting reflection for class {$class}", 0, $e);
        }
        $rm = $rc->getConstructor();
        if ($rm) {
            $parameters = $this->expandParameters($rm->getParameters());
            return $rc->newInstanceArgs($parameters);
        }
        if ($rc->isInterface()) {
            throw new LogicException("Can't instantiate interface {$class}");
        }
        return $rc->newInstance();
    }

    /**
     * Expands function parameters & returns their instance objects
     *
     * @param ReflectionParameter[] $rps
     * @return object[]
     */
    private function expandParameters(array $rps): array
    {
        $parameters = [];
        foreach ($rps as $rp) {
            $type = $rp->getType();
            if ($type === null) {
                throw new LogicException("Missing type for parameter {$rp->getName()}");
            }
            if (!($type instanceof ReflectionNamedType)) {
                throw new LogicException("Union types are not supported for parameter {$rp->getName()}");
            }
            $class = $type->getName();
            $parameters[] = $this->getObject($class);
        }
        return $parameters;
    }
}