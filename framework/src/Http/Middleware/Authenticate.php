<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Middleware;

use Sadl\Framework\Http\RedirectResponse;
use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;
use Sadl\Framework\Session\Session;
use Sadl\Framework\Session\SessionInterface;

class Authenticate implements MiddlewareInterface
{
    /**
     * @param \Sadl\Framework\Session\SessionInterface $session
     */
    public function __construct(private SessionInterface $session)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        $this->session->start();

        if (!$this->session->has(Session::AUTH_KEY)) {
            $this->session->setFlash('error', 'Please sign in');
            return new RedirectResponse('/login');
        }

        return $requestHandler->handle($request);
    }
}