<?php declare(strict_types=1);

namespace Sadl\Framework\Routing;

use Sadl\Framework\Http\Request;

interface RouterInterface
{
    /**
     * @param \Sadl\Framework\Http\Request $request
     *
     * @return mixed
     */
    public function dispatch(Request $request): mixed;
}