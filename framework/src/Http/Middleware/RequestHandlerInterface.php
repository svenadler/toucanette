<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Middleware;

use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;

interface RequestHandlerInterface
{
    /**
     * @param \Sadl\Framework\Http\Request $request
     *
     * @return \Sadl\Framework\Http\Response
     */
    public function handle(Request $request): Response;
}