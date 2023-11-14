<?php declare(strict_types=1);

namespace Sadl\Framework\Routing;

use Psr\Container\ContainerInterface;
use Sadl\Framework\Http\Request;

interface RouterInterface
{
    /**
     * @param \Sadl\Framework\Http\Request $request
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return array
     */
    public function dispatch(Request $request, ContainerInterface $container): array;

    /**
     * @param array $routes
     *
     * @return void
     */
    public function setRoutes(array $routes): void;
}