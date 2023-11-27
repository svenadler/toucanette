<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Middleware;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Sadl\Framework\Http\Exception\HttpException;
use Sadl\Framework\Http\Exception\HttpRequestMethodException;
use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;

class ExtractRouteInfo implements MiddlewareInterface
{
    /**
     * @param array $routes
     */
    public function __construct(private array $routes) // container is used to inject the array of routes through constructor
    {
    }

    /**
     * @inheritDoc
     * @throws \Sadl\Framework\Http\Exception\HttpException
     */
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
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

/*
 0 => 1
  1 => array:3 [▼
    0 => "App\Controller\DashboardController"
    1 => "index"
    2 => array:1 [▼
      0 => "Sadl\Framework\Http\Middleware\Authenticate"
    ]
  ]
  2 => []
]
 */
        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                // Set $request->routeHandler
                $request->setRouteHandler($routeInfo[1]);
                // Set $request->routeHandlerArgs
                $request->setRouteHandlerArgs($routeInfo[2]);
                // Inject route middleware on handler e.g. in LoginController
                if (is_array($routeInfo[1]) && isset($routeInfo[1][2])) {
                    $requestHandler->injectMiddleware($routeInfo[1][2]);
                }
                break;
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

        return $requestHandler->handle($request);
    }
}