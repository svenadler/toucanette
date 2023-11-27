<?php declare(strict_types=1);

namespace App\Controller;

use App\Form\User\RegistrationForm;
use App\Repository\User\UserMapper;
use Sadl\Framework\Authentication\SessionAuthentication;
use Sadl\Framework\Controller\AbstractController;
use Sadl\Framework\Http\RedirectResponse;
use Sadl\Framework\Http\Response;

class RegistrationController extends AbstractController
{
    /**
     * @param \App\Repository\User\UserMapper $userMapper
     * @param \Sadl\Framework\Authentication\SessionAuthentication $authComponent
     */
    public function __construct(
        private UserMapper $userMapper,
        private SessionAuthentication $authComponent
    )
    {
    }

    /**
     * @return \Sadl\Framework\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(): Response
    {
        return $this->render('register.twig');
    }

    /**
     * @return \Sadl\Framework\Http\Response
     * @throws \Doctrine\DBAL\Exception
     */
    public function register(): Response
    {
        // Create a form model which will:
        // - validate fields
        // - map the fields to User object properties
        // - ultimately save the new User to the DB
        $form = new RegistrationForm($this->userMapper);
        $form->setFields(
            $this->request->input('username'),
            $this->request->input('password')
        );

        // Validate
        // If validation errors,
        if ($form->hasValidationErrors()) {
            // add to session, redirect to form
            foreach ($form->getValidationErrors() as $error) {
                $this->request->getSession()->setFlash('error', $error);
            }

            return new RedirectResponse('/register');
        }

        // register the user by calling $form->save() - returns also a user
        $user = $form->save();

        // Add a session success message
        $this->request->getSession()->setFlash(
            'success',
            sprintf('User %s successful created! 🚀', $user->getUsername())
        );

        // Log the user in
        $this->authComponent->login($user);

        // Redirect to somewhere useful
        return new RedirectResponse('/dashboard');

    }
}