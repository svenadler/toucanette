<?php declare(strict_types=1);

namespace Sadl\Framework\Dbal\Event;

use Sadl\Framework\Dbal\Entity;
use Sadl\Framework\EventDispatcher\Event;

// Not just related to posts, instead it is called after something is persisted in the db
class PostPersist extends Event
{
    /**
     * @param \Sadl\Framework\Dbal\Entity $entity
     */
    public function __construct(private Entity $entity) //i.e. item which has been persisted to the db when this evemt should be fired
    {
    }
}