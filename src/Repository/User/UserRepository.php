<?php declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Sadl\Framework\Authentication\AuthRepositoryInterface;
use Sadl\Framework\Authentication\AuthUserInterface;

class UserRepository implements AuthRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @inheritDoc
     * @throws \Doctrine\DBAL\Exception
     * @throws \Exception
     */
    public function findByUsername(string $username): ?AuthUserInterface
    {
        // Create a query builder
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('id', 'username', 'password', 'created_at')
            ->from('users')
            ->where('username = :username')
            ->setParameter('username', $username);

        $result = $queryBuilder->executeQuery();

        $row = $result->fetchAssociative();

        if (!$row) {
            return null;
        }

        $user = new User(
            $row['id'],
            $row['username'],
            $row['password'],
            new DateTimeImmutable($row['created_at'])
        );

        return $user;
    }
}