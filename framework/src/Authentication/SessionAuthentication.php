<?php declare(strict_types=1);

namespace Sadl\Framework\Authentication;

use Sadl\Framework\Session\Session;
use Sadl\Framework\Session\SessionInterface;

class SessionAuthentication implements SessionAuthInterface
{
    /**
     * @var \Sadl\Framework\Authentication\AuthUserInterface
     */
    private AuthUserInterface $user;

    /**
     * @param \Sadl\Framework\Authentication\AuthRepositoryInterface $authRepository
     * @param \Sadl\Framework\Session\SessionInterface $session
     */
    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private SessionInterface $session
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function authenticate(string $username, string $password): bool
    {
        // query db for user using username
        $user = $this->authRepository->findByUsername($username);

        if (!$user) {
            return false;
        }

        // Does the hashed user pw match the hash of the attempted password
        if (!password_verify($password, $user->getPassword())) {
            // return false
            return false;
        }

        // if yes, log the user in
        $this->login($user);

        // return true
        return true;
    }

    /**
     * @inheritDoc
     */
    public function login(AuthUserInterface $user): void
    {
        //start a session
        $this->session->start();

        //log the user in
        $this->session->set(Session::AUTH_KEY, $user->getAuthId());

        //set the user
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function logout(): void
    {
        $this->session->remove(Session::AUTH_KEY);
    }

    /**
     * @inheritDoc
     */
    public function getUser(): AuthUserInterface
    {
        return $this->user;
    }
}