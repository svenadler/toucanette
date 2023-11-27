<?php declare(strict_types=1);

namespace Sadl\Framework\Session;

class Session implements SessionInterface
{
    /**
     * @var string
     */
    private const FLASH_KEY = 'flash';

    /**
     * @var string
     */
    public const AUTH_KEY = 'auth_id';

    /**
     * @return void
     * @throws \Exception
     */
    public function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_start();

        if (!$this->has('csrf_token')) {
            $this->set('csrf_token', bin2hex(random_bytes(32)));
        }
    }

    /**
     * @param string $key
     * @param $value
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @param $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getFlash(string $type): array
    {
        $flash = $this->get(self::FLASH_KEY) ?? [];
        if (isset($flash[$type])) { // i.e. success
            $messages = $flash[$type];
            unset($flash[$type]); // unset as soon it is retrieved
            $this->set(self::FLASH_KEY, $flash);

            return $messages;
        }

        return [];
    }

    /**
     * @param string $type
     * @param string $message
     *
     * @return void
     */
    public function setFlash(string $type, string $message): void
    {
        $flash = $this->get(self::FLASH_KEY) ?? [];
        $flash[$type][] = $message;
        $this->set(self::FLASH_KEY, $flash);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasFlash(string $type): bool
    {
        return isset($_SESSION[self::FLASH_KEY][$type]);
    }

    /**
     * @return void
     */
    public function clearFlash(): void
    {
        unset($_SESSION[self::FLASH_KEY]);
    }

    /**
     * used in base.twig
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->has(self::AUTH_KEY);
    }
}