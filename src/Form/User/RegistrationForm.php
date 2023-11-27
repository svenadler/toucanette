<?php declare(strict_types=1);

namespace App\Form\User;

use App\Entity\User;
use App\Repository\User\UserMapper;

class RegistrationForm
{
    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @param \App\Repository\User\UserMapper $userMapper
     */
    public function __construct(private UserMapper $userMapper)
    {
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return void
     */
    public function setFields(string $username, string $password): void
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return \App\Entity\User
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(): User
    {
        $user = User::create($this->username, $this->password);

        $this->userMapper->save($user);

        return $user;
    }

    /**
     * @return bool
     */
    public function hasValidationErrors(): bool
    {
        return count($this->getValidationErrors()) > 0;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        if (!empty($this->errors)) {
            return $this->errors;
        }

        // username length
        if (strlen($this->username) < 5 || strlen($this->username) > 20) {
            $this->errors[] = 'Username must be between 5 and 20 characters';
        }

        // username char type
        if (!preg_match('/^\w+$/', $this->username)) {
            $this->errors[] = 'Username can only consist of word characters without spaces';
        }

        // password length
        if (strlen($this->password) < 8) {
            $this->errors[] = 'Password must be at least 8 characters';
        }

        return $this->errors;
    }
}