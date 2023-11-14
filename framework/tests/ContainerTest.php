<?php declare(strict_types=1);

namespace Sadl\Framework\Tests;

use PHPUnit\Framework\TestCase;
use Sadl\Framework\Container\Container;
use Sadl\Framework\Container\Exception\ContainerException;

class ContainerTest extends TestCase
{
    /**
     * @return void
     * @throws \Sadl\Framework\Container\Exception\ContainerException
     */
    public function testServiceCanBeRetrievedFromContainer()
    {
        $container = new Container();

        $container->add('dependant-class', DependantClass::class);

        $this->assertInstanceOf(DependantClass::class, $container->get('dependant-class'));
    }

    /**
     * @return void
     * @throws \Sadl\Framework\Container\Exception\ContainerException
     */
    public function testServiceNotFound()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->add('foobar');
    }

    /**
     * @return void
     * @throws \Sadl\Framework\Container\Exception\ContainerException
     */
    public function testContainerHasService()
    {
        $container = new Container();

        $container->add('dependant-class', DependantClass::class);

        $this->assertTrue($container->has('dependant-class'));
        $this->assertFalse($container->has('non-existing-class'));
    }

    /**
     * @return void
     * @throws \Sadl\Framework\Container\Exception\ContainerException
     */
    public function testServicesRecursiveAutowiring()
    {
        $container = new Container();

        $dependantService = $container->get(DependantClass::class);

        $dependancyService = $dependantService->getDependency();

        $this->assertInstanceOf(DependencyClass::class, $dependancyService);
        $this->assertInstanceOf(SubDependencyClass::class, $dependancyService->getSubDependency());
    }
}