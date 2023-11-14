<?php declare(strict_types=1);

namespace Sadl\Framework\Http;

class Response
{
    /**
     * @var int
     */
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * @param string|null $content
     * @param int $status
     * @param array $headers
     */
    public function __construct(
        private ?string $content = '',
        private int $status = 200,
        private array $headers = []
    ) {
        http_response_code($this->status);
    }

    /**
     * @return void
     */
    public function send(): void
    {
        echo $this->content;
    }

    /**
     * @param string|null $content
     *
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}