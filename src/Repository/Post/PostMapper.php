<?php declare(strict_types=1);

namespace App\Repository\Post;

use App\Entity\Post;
use Sadl\Framework\Dbal\DataMapper;

class PostMapper
{
    /**
     * @param \Sadl\Framework\Dbal\DataMapper $dataMapper
     */
    public function __construct(private DataMapper $dataMapper)
    {
    }

    /**
     * @param \App\Entity\Post $post
     *
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(Post $post): void
    {
        $stmt = $this->dataMapper->getConnection()->prepare(
            /** @lang text */
            "INSERT INTO posts (title, body, created_at)
            VALUES (:title, :body, :created_at)"
        );

        $stmt->bindValue(':title', $post->getTitle());
        $stmt->bindValue(':body', $post->getBody());
        $stmt->bindValue(':created_at', $post->getDateCreatedAt()->format('Y-m-d H:i:s'));

        $stmt->executeStatement();

        $id = $this->dataMapper->save($post);

        $post->setId((int)$id);
    }
}