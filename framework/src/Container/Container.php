<?php declare(strict_types=1);

namespace Sadl\Framework\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use Sadl\Framework\Container\Exception\ContainerException;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    protected array $services = [];

    /**
     * @param string $id
     * @param string|object|null $concrete
     *
     * @return void
     * @throws \Sadl\Framework\Container\Exception\ContainerException
     */
    public function add(string $id, string|object $concrete = null): void
    {
        if (null === $concrete) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be found");
            }

            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }

    /**
     * @param string $id
     *
     * @return mixed
     * @throws \Sadl\Framework\Container\Exception\ContainerException|\ReflectionException
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be resolved");
            }

            $this->add($id);
        }

        $object = $this->resolve($this->services[$id]);

        return $object;
    }

    /**
     * @param $class
     *
     * @return object|null
     * @throws \ReflectionException
     * @throws \Sadl\Framework\Container\Exception\ContainerException
     */
    private function resolve($class): ?object
    {
        // 1. Instantiate a Reflection class (dump and check)
        $reflectionClass = new ReflectionClass($class);

        // 2. Use Reflection to try to obtain a class constructor
        $constructor = $reflectionClass->getConstructor();

        // 3. If there is no constructor, simply instantiate
        if (null === $constructor) {
            return $reflectionClass->newInstance();
        }

        // 4. Get the constructor parameters
        $constructorParams = $constructor->getParameters();

        // 5. Obtain dependencies
        $classDependencies = $this->resolveClassDependencies($constructorParams);

        // 6. Instantiate with dependencies
        $service = $reflectionClass->newInstanceArgs($classDependencies);

        // 7. Return the object
        return $service;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @param array $reflectionParams
     *
     * @return array
     * @throws \ReflectionException
     * @throws \Sadl\Framework\Container\Exception\ContainerException
     */
    private function resolveClassDependencies(array $reflectionParams): array
    {
        $classDependencies = [];

        /**
         * @var \ReflectionParameter $reflectionParam
         */
        foreach ($reflectionParams as $reflectionParam) {
            $serviceType = $reflectionParam->getType();

            $service = $this->get($serviceType->getName());

            $classDependencies[] = $service;
        }

        return $classDependencies;
    }
}