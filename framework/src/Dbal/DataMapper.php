<?php declare(strict_types=1);

namespace Sadl\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Sadl\Framework\Dbal\Event\PostPersist;
use Sadl\Framework\EventDispatcher\EventDispatcher;

class DataMapper
{
    /**
     * @param \Doctrine\DBAL\Connection $connection
     * @param \Sadl\Framework\EventDispatcher\EventDispatcher $eventDispatcher
     */
    public function __construct(
        private Connection $connection,
        private EventDispatcher $eventDispatcher
    )
    {
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(Entity $subject): int|string|null
    {
        // Dispatch PostPersist event
        $this->eventDispatcher->dispatch(new PostPersist($subject)); // subject is an instance of entity

        // Return lastInsertId
        return $this->connection->lastInsertId();
    }
}