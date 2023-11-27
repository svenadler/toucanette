<?php declare(strict_types=1);

namespace App\Controller;

use Sadl\Framework\Controller\AbstractController;
use Sadl\Framework\Http\Response;

class DashboardController extends AbstractController
{
    /**
     * @return \Sadl\Framework\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(): Response
    {
        return $this->render('dashboard.twig');
    }
}