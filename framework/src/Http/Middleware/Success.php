<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Middleware;

use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;

class Success implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        return new Response('⋆｡ﾟ☁︎｡⋆｡ ﾟ☾ ﾟ｡⋆ OMG it worked!! 🌌', 200);
    }
}