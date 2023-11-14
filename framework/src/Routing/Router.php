<?php declare(strict_types=1);

namespace Sadl\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;
use Sadl\Framework\Exception\HttpException;
use Sadl\Framework\Exception\HttpRequestMethodException;
use Sadl\Framework\Http\Request;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    /**
     * @var array
     */
    protected array $routes = [];

    /**
     * @param \Sadl\Framework\Http\Request $request
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Sadl\Framework\Exception\HttpException
     * @throws \Sadl\Framework\Exception\HttpRequestMethodException
     */
    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $routeInfo = $this->extractRouteInformation($request);

        [$handler, $vars] = $routeInfo;

        if (is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);
            $handler = [$controller, $method];
        }

        return [$handler, $vars];
    }

    /**
     * @param array $routes
     *
     * @return void
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * @param \Sadl\Framework\Http\Request $request
     *
     * @return array
     * @throws \Sadl\Framework\Exception\HttpException
     * @throws \Sadl\Framework\Exception\HttpRequestMethodException
     */
    private function extractRouteInformation(Request $request): array
    {
        // Create a dispatcher
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            foreach ($this->routes as $route) {
                $routeCollector->addRoute(...$route);
            }
        });

        // Dispatch a URI, to obtain the route info
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $exception = new HttpRequestMethodException("The allowed methods are $allowedMethods");
                $exception->setStatusCode(405);
                throw $exception;
            default:
                $exception = new HttpException('Not found');
                $exception->setStatusCode(404);
                throw $exception;
        }
    }
}