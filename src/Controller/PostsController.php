<?php declare(strict_types=1);

namespace App\Controller;

use Sadl\Framework\Http\Response;

class PostsController
{
    /**
     * @param int $id
     *
     * @return \Sadl\Framework\Http\Response
     */
    public function show(int $id): Response
    {
        $content = "This is post $id";

        return new Response($content);
    }
}