<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Middleware;

use Psr\Container\ContainerInterface;
use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;
use Sadl\Framework\Routing\RouterInterface;

class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

        $response = call_user_func_array($routeHandler, $vars);

        return $response;
    }
}