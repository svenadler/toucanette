<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Middleware;

use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;
use Sadl\Framework\Session\SessionInterface;

class StartSession implements MiddlewareInterface
{
    /**
     * @param \Sadl\Framework\Session\SessionInterface $session
     * @param string $apiPrefix
     */
    public function __construct(
        private SessionInterface $session,
        private string $apiPrefix = '/api/'
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        if (!str_starts_with($request->getPathInfo(), $this->apiPrefix)) {
        $this->session->start();

        $request->setSession($this->session);
        }

        return $requestHandler->handle($request);
    }
}