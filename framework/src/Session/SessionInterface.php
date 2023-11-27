<?php declare(strict_types=1);

namespace Sadl\Framework\Session;

interface SessionInterface
{
    /**
     * @return void
     */
    public function start(): void;

    /**
     * @param string $key
     * @param $value
     *
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     * @param $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null): mixed;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void;

    /**
     * @param string $type
     *
     * @return array
     */
    public function getFlash(string $type): array;

    /**
     * @param string $type
     * @param string $message
     *
     * @return void
     */
    public function setFlash(string $type, string $message): void;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasFlash(string $type): bool;

    /**
     * @return void
     */
    public function clearFlash(): void;

    /**
     * @return bool
     */
    public function isAuthenticated(): bool;
}