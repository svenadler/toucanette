<?php declare(strict_types=1);

namespace Sadl\Framework\Http;

readonly class Request
{
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
    ) {
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
}