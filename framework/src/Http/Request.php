<?php declare(strict_types=1);

namespace Sadl\Framework\Http;

use Sadl\Framework\Session\SessionInterface;

readonly class Request
{
    /**
     * @var \Sadl\Framework\Session\SessionInterface
     */
    private SessionInterface $session;

    /**
     * @var mixed
     */
    private mixed $routeHandler;

    /**
     * @var array
     */
    private array $routeHandlerArgs;

    /**
     * @param array $getParams
     * @param array $postParams
     * @param array $cookies
     * @param array $files
     * @param array $server
     */
    public function __construct(
        public array $getParams,
        public array $postParams,
        public array $cookies,
        public array $files,
        public array $server
    )
    {
    }

    /**
     * @return static
     */
    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * @return string
     */
    public function getPathInfo(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * @return \Sadl\Framework\Session\SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param \Sadl\Framework\Session\SessionInterface $session
     *
     * @return void
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function input($key): mixed
    {
        return $this->postParams[$key];
    }

    public function getRouteHandler(): mixed
    {
        return $this->routeHandler;
    }

    public function setRouteHandler(mixed $routeHandler): void
    {
        $this->routeHandler = $routeHandler;
    }

    public function getRouteHandlerArgs(): array
    {
        return $this->routeHandlerArgs;
    }

    public function setRouteHandlerArgs(array $routeHandlerArgs): void
    {
        $this->routeHandlerArgs = $routeHandlerArgs;
    }
}