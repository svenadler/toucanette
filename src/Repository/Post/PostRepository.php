<?php

namespace App\Repository\Post;

use App\Entity\Post;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Sadl\Framework\Http\Exception\NotFoundException;

class PostRepository
{
    /**
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @param int $id
     *
     * @return \App\Entity\Post|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function findById(int $id): ?Post
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('id', 'title', 'body', 'created_at')
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id);

        $result = $queryBuilder->executeQuery();

        $row = $result->fetchAssociative();

        if (!$row) {
            return null;
        }

        $post = Post::create(
            $row['title'],
            $row['body'],
            $row['id'],
            new DateTimeImmutable($row['created_at'])
        );

        return $post;
    }

    /**
     * @param int $id<<<<s
     *
     * @return \App\Entity\Post
     * @throws \Doctrine\DBAL\Exception
     * @throws \Sadl\Framework\Http\Exception\NotFoundException
     */
    public function findOrFail(int $id): Post
    {
        $post = $this->findById($id);

        if (!$post) {
            throw new NotFoundException(sprintf('Post with ID %d not found', $id));
        }

        return $post;
    }
}