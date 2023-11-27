<?php declare(strict_types=1);

namespace Sadl\Framework\Http\Exception;

use Exception;

class HttpException extends Exception
{
    /**
     * @var int
     */
    private int $statusCode = 400;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return void
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}