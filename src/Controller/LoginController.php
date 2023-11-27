<?php declare(strict_types=1);

namespace App\Controller;

use Sadl\Framework\Authentication\SessionAuthentication;
use Sadl\Framework\Controller\AbstractController;
use Sadl\Framework\Http\RedirectResponse;
use Sadl\Framework\Http\Response;

class LoginController extends AbstractController
{
    /**
     * @param \Sadl\Framework\Authentication\SessionAuthentication $authComponent
     */
    public function __construct(private SessionAuthentication $authComponent)
    {
    }

    /**
     * @return \Sadl\Framework\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(): Response
    {
        return $this->render('login.twig');
    }

    /**
     * @return \Sadl\Framework\Http\Response
     */
    public function login(): Response
    {
        // Attempt to authenticate the user using a security component (bool)
        // create a session for the user
        $userIsAuthenticated = $this->authComponent->authenticate(
            $this->request->input('username'),
            $this->request->input('password')
        );

        // If successful, retrieve the user
        if (!$userIsAuthenticated) {
            $this->request->getSession()->setFlash('error', 'Bad creds');
            return new RedirectResponse('/login');
        }

//        $user = $this->authComponent->getUser();

        $this->request->getSession()->setFlash('success', 'You are now logged in');

        // Redirect the user to intended location
        return new RedirectResponse('/dashboard');
    }

    public function logout(): Response
    {
        // Log the user out
        $this->authComponent->logout();

        // Set a logout session message
        $this->request->getSession()->setFlash('success', 'Bye..see you soon!');

        // Redirect to login page
        return new RedirectResponse('/login');
    }
}