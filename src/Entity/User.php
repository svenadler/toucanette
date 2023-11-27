<?php declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Sadl\Framework\Authentication\AuthUserInterface;
use Sadl\Framework\Dbal\Entity;

class User extends Entity implements AuthUserInterface
{
    /**
     * @param int|null $id
     * @param string $username
     * @param string $password
     * @param \DateTimeImmutable $dateCreatedAt
     */
    public function __construct(
        private ?int $id,
        private string $username,
        private string $password,
        private DateTimeImmutable $dateCreatedAt
    )
    {
    }

    /**
     * @param string $username
     * @param string $plainPassword
     *
     * @return self
     */
    public static function create(string $username, string $plainPassword): self
    {
        return new self(
            null,
            $username,
            password_hash($plainPassword, PASSWORD_DEFAULT),
            new DateTimeImmutable()
        );
    }

    /**
     * @inheritDoc
     */
    public function getAuthId(): int|string
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateCreatedAt(): DateTimeImmutable
    {
        return $this->dateCreatedAt;
    }
}