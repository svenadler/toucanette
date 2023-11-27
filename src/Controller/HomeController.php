<?php declare(strict_types=1);

namespace App\Controller;

use App\Widget;
use Sadl\Framework\Controller\AbstractController;
use Sadl\Framework\Http\Response;

class HomeController extends AbstractController
{
//    /**
//     * @param \App\Widget $widget
//     */
//    public function __construct(private Widget $widget) // TODO find out if Widget is really needed here
//    {
//    }

    /**
     * @return \Sadl\Framework\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(): Response
    {
        return $this->render('home.twig');
    }
}