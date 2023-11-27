<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Repository\Post\PostMapper;
use App\Repository\Post\PostRepository;
use Sadl\Framework\Controller\AbstractController;
use Sadl\Framework\Http\RedirectResponse;
use Sadl\Framework\Http\Response;

class PostsController extends AbstractController
{
    /**
     * @param \App\Repository\Post\PostMapper $postMapper
     * @param \App\Repository\Post\PostRepository $postRepository
     */
    public function __construct(
        private PostMapper $postMapper,
        private PostRepository $postRepository,
    )
    {
    }

    /**
     * @param int $id
     *
     * @return \Sadl\Framework\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface|\Doctrine\DBAL\Exception
     * @throws \Sadl\Framework\Http\Exception\NotFoundException
     */
    public function show(int $id): Response
    {
        $post = $this->postRepository->findOrFail($id);

        return $this->render('posts.twig', [
            'post' => $post
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

    /**
     * @return \Sadl\Framework\Http\Response
     * @throws \Doctrine\DBAL\Exception
     */
    public function store(): Response
    {
        $title = $this->request->postParams['title'];
        $body = $this->request->postParams['body'];

        $post = Post::create($title, $body);

        $this->postMapper->save($post);

        $this->request->getSession()->setFlash(
            'success',
            sprintf('Post "%s" successfully created 🦜', $title)
        );

        return new RedirectResponse('/posts');
    }
}