<?php declare(strict_types=1);

namespace Sadl\Framework\Controller;

use Psr\Container\ContainerInterface;
use Sadl\Framework\Http\Request;
use Sadl\Framework\Http\Response;

abstract class AbstractController
{
    /**
     * @var \Psr\Container\ContainerInterface|null
     */
    protected ?ContainerInterface $container = null;

    /**
     * @var \Sadl\Framework\Http\Request
     */
    protected Request $request;

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
     * @param \Sadl\Framework\Http\Request $request
     *
     * @return void
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
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
        // build twig env from TwigFactory and call render from it
        $content = $this->container->get('twig')->render($template, $parameters);

        $response ??= new Response();

        $response->setContent($content);

        return $response;
    }
}