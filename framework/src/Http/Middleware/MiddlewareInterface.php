<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Middleware;

use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;

interface MiddlewareInterface
{
    /**
     * @param \Sadl\Framework\Http\Request $request
     * @param \Sadl\Framework\Http\Middleware\RequestHandlerInterface $requestHandler
     *
     * @return \Sadl\Framework\Http\Response
     */
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response;
}