<?php
declare(strict_types=1);

namespace Paket\Bero;

interface Bero
{
    /**
     * Adds callable to be called when instantiating class
     *
     * @param string $class
     * @param callable $callable
     */
    public function addCallable(string $class, callable $callable): void;

    /**
     * Maps interface to a class implementation
     *
     * @param string $interface
     * @param string $class
     */
    public function addInterface(string $interface, string $class): void;

    /**
     * Adds instance object for a class
     *
     * @param string $class
     * @param object $object
     */
    public function addObject(string $class, object $object): void;

    /**
     * Calls callable by expanding its parameters & returning the result
     *
     * @param callable $callable
     * @return mixed
     */
    public function callCallable(callable $callable);

    /**
     * Gets instance object for a class
     *
     * @param string $class
     * @return object
     */
    public function getObject(string $class): object;
}