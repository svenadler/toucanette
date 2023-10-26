<?php declare(strict_types=1);

namespace Sadl\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Sadl\Framework\Exception\HttpException;
use Sadl\Framework\Exception\HttpRequestMethodException;
use Sadl\Framework\Http\Request;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    /**
     * @param \Sadl\Framework\Http\Request $request
     *
     * @return array
     * @throws \Sadl\Framework\Exception\HttpException
     * @throws \Sadl\Framework\Exception\HttpRequestMethodException
     */
    public function dispatch(Request $request): array
    {
        $routeInfo = $this->extractRouteInformation($request);

        [$handler, $vars] = $routeInfo;

        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $handler = [new $controller, $method];
        }

        return [$handler, $vars];
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
            $routes = include BASE_PATH.'/routes/web.php';

            foreach ($routes as $route) {
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