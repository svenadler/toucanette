<?php declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User;
use Sadl\Framework\Dbal\DataMapper;

class UserMapper
{
    /**
     * @param \Sadl\Framework\Dbal\DataMapper $dataMapper
     */
    public function __construct(private DataMapper $dataMapper)
    {
    }

    /**
     * @param \App\Entity\User $user
     *
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(User $user): void
    {
        $stmt = $this->dataMapper->getConnection()->prepare(
            /** @lang text */
            "INSERT INTO users (username, password, created_at)
            VALUES (:username, :password, :created_at)"
        );

        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':created_at', $user->getDateCreatedAt()->format('Y-m-d H:i:s'));

        $stmt->executeStatement();

        $id = $this->dataMapper->save($user);

        $user->setId((int)$id);
    }

}