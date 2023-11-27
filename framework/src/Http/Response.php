<?php declare(strict_types=1);

namespace Sadl\Framework\Http;

class Response
{
    /**
     * @var int
     */
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * @var int
     */
    public const HTTP_FORBIDDEN = 403;

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
        // Must be set before sending content
        // So best to create on instantiation like here
        http_response_code($this->status);
    }

    /**
     * @return void
     */
    public function send(): void
    {
        //start output buffering
        ob_start();

        foreach ($this->headers as $key => $value) {
            header("$key: $value"); //Content-Length: xxxx
        }

        // This will actually add the content to the buffer
        echo $this->content; // content comes from AbstractController

        // Flush the buffer, sending the content to the client
        ob_end_flush();
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

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return void
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @param string $header
     *
     * @return mixed
     */
    public function getHeader(string $header): mixed
    {
        return $this->headers[$header];
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function setHeader($key, $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }
}