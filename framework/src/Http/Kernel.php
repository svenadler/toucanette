<?php declare(strict_types=1);

namespace Sadl\Framework\Http;

use Psr\Container\ContainerInterface;
use Sadl\Framework\Exception\HttpException;
use Sadl\Framework\Routing\RouterInterface;

class Kernel
{
    /**
     * @var string
     */
    private string $appEnv;

    /**
     * @param \Sadl\Framework\Routing\RouterInterface $router
     * @param \Psr\Container\ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    )
    {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    /**
     * @param \Sadl\Framework\Http\Request $request
     *
     * @return \Sadl\Framework\Http\Response
     */
    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

            $response = call_user_func_array($routeHandler, $vars);

        } catch (\Exception $exception) {
            $response = $this->createExceptionResponse($exception);
        }

        return $response;
    }

    /**
     * @param \Exception $exception
     *
     * @return \Sadl\Framework\Http\Response
     * @throws \Exception
     */
    private function createExceptionResponse(\Exception $exception): Response
    {
        if (in_array($this->appEnv, ['dev', 'test'])) {
            throw $exception;
        }

        if ($exception instanceof HttpException) {
            return new Response($exception->getMessage(), $exception->getStatusCode());
        }

        return new Response('Server error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}