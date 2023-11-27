<?php declare(strict_types=1);

namespace Sadl\Framework\Authentication;

interface AuthUserInterface
{
    /**
     * @return int|string
     */
    public function getAuthId(): int|string;

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return string
     */
    public function getPassword(): string;
}