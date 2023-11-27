<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Middleware;

use Psr\Container\ContainerInterface;
use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;

class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var string[]
     */
    private array $middleware = [
        ExtractRouteInfo::class,
        StartSession::class,
        VerifyCsrfToken::class,
        RouterDispatch::class
    ];

    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request): Response
    {
        // If there are no middleware classes to execute, return a default response
        // A response should have been returned before the list becomes empty
        if (empty($this->middleware)) {
            return new Response("It's totally borked, mate. Contact support", 500);
        }

        // Get the next middleware class to execute
        $middlewareClass = array_shift($this->middleware);

        // Create a new instance of the middleware call process on it
        $middleware = $this->container->get($middlewareClass);

        $response = $middleware->process($request, $this); // handle is called in the middleware classes, so $this is needed, see middlewareInterface

        return $response;
    }

    /**
     * @param array $middleware
     *
     * @return void
     */
    public function injectMiddleware(array $middleware): void
    {
        array_splice($this->middleware, 0, 0, $middleware);
//        dd($this->middleware);
    }
}