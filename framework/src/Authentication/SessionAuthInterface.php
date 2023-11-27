<?php declare(strict_types=1);

namespace Sadl\Framework\Authentication;

interface SessionAuthInterface
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function authenticate(string $username, string $password): bool;

    /**
     * @param \Sadl\Framework\Authentication\AuthUserInterface $user
     *
     * @return mixed
     */
    public function login(AuthUserInterface $user);

    /**
     * @return mixed
     */
    public function logout();

    /**
     * @return \Sadl\Framework\Authentication\AuthUserInterface
     */
    public function getUser(): AuthUserInterface;
}