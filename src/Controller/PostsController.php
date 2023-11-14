<?php declare(strict_types=1);

namespace App\Controller;

use Sadl\Framework\Controller\AbstractController;
use Sadl\Framework\Http\Response;

class PostsController extends AbstractController
{
    /**
     * @param int $id
     *
     * @return \Sadl\Framework\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function show(int $id): Response
    {
        return $this->render('posts.twig', [
            'postId' => $id
        ]);
    }

    /**
     * @return \Sadl\Framework\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function create(): Response
    {
        return $this->render('create-post.twig');
    }
}