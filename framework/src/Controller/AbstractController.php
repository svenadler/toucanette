<?php declare(strict_types=1);

namespace Sadl\Framework\Controller;

use Psr\Container\ContainerInterface;
use Sadl\Framework\Http\Response;

abstract class AbstractController
{
    /**
     * @var \Psr\Container\ContainerInterface|null
     */
    protected ?ContainerInterface $container = null;

    /**
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @param string $template
     * @param array $parameters
     * @param \Sadl\Framework\Http\Response|null $response
     *
     * @return \Sadl\Framework\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function render(string $template, array $parameters = [], Response $response = null): Response
    {
        $content = $this->container->get('twig')->render($template, $parameters);

        $response ??= new Response();

        $response->setContent($content);

        return $response;
    }
}