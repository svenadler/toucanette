<?php declare(strict_types=1);

namespace Sadl\Framework\Http;

use Exception;
use Psr\Container\ContainerInterface;
use Sadl\Framework\EventDispatcher\EventDispatcher;
use Sadl\Framework\Http\Event\ResponseEvent;
use Sadl\Framework\Http\Exception\HttpException;
use Sadl\Framework\Http\Middleware\RequestHandlerInterface;

class Kernel
{
    /**
     * @var string
     */
    private string $appEnv;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param \Sadl\Framework\Http\Middleware\RequestHandlerInterface $requestHandler
     * @param \Sadl\Framework\EventDispatcher\EventDispatcher $eventDispatcher
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(
        private ContainerInterface $container,
        private RequestHandlerInterface $requestHandler,
        private EventDispatcher $eventDispatcher
    )
    {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    /**
     * @param \Sadl\Framework\Http\Request $request
     *
     * @return \Sadl\Framework\Http\Response
     * @throws \Exception
     */
    public function handle(Request $request): Response
    {
        try {
            $response = $this->requestHandler->handle($request);

        } catch (Exception $exception) {
            $response = $this->createExceptionResponse($exception);
        }

//        $response->setStatus(500);

        // sending event
        $this->eventDispatcher->dispatch(new ResponseEvent($request, $response));

        // when response is finally returned send will be called on it
        return $response;
    }

    /**
     * @param \Exception $exception
     *
     * @return \Sadl\Framework\Http\Response
     * @throws \Exception
     */
    private function createExceptionResponse(Exception $exception): Response
    {
        if (in_array($this->appEnv, ['dev', 'test'])) {
            throw $exception;
        }

        if ($exception instanceof HttpException) {
            return new Response($exception->getMessage(), $exception->getStatusCode());
        }

        return new Response('Server error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param \Sadl\Framework\Http\Request $request
     * @param \Sadl\Framework\Http\Response $response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        $request->getSession()?->clearFlash();
    }
}