<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Event;

use Sadl\Framework\EventDispatcher\Event;
use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;

// fired before a Response is returned
class ResponseEvent extends Event
{
    /**
     * @param \Sadl\Framework\Http\Request $request
     * @param \Sadl\Framework\Http\Response $response
     */
    public function __construct(
        private Request $request,
        private Response $response
    )
    {
    }

    /**
     * @return \Sadl\Framework\Http\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return \Sadl\Framework\Http\Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}