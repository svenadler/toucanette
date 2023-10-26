<?php declare(strict_types=1);

namespace Sadl\Framework\Http;

class Response
{
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
}