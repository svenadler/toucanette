<?php declare(strict_types=1);

namespace App\Controller;

use Sadl\Framework\Http\Response;

class HomeController
{
    /**
     * @return \Sadl\Framework\Http\Response
     */
    public function index(): Response
    {
        $content = '<h1>Hello World</h1>';

        return new Response($content);
    }
}