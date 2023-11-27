<?php declare(strict_types=1);

namespace Sadl\Framework\Routing;

use Psr\Container\ContainerInterface;
use Sadl\Framework\Controller\AbstractController;
use Sadl\Framework\Http\Request;

class Router implements RouterInterface
{
    /**
     * @inheritDoc
     */
    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $routeHandler = $request->getRouteHandler();
        $routeHandlerArgs = $request->getRouteHandlerArgs();

        if (is_array($routeHandler)) {
            [$controllerId, $method] = $routeHandler;
            $controller = $container->get($controllerId);
            if (is_subclass_of($controller, AbstractController::class)) {
                $controller->setRequest($request); // controller now is able to access information on the request object
            }
            $routeHandler = [$controller, $method];
        }

        return [$routeHandler, $routeHandlerArgs];
    }
}