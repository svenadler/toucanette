<?php declare(strict_types=1);

namespace App\Provider;

use App\EventListener\ContentLengthListener;
use App\EventListener\InternalErrorListener;
use Sadl\Framework\Dbal\Event\PostPersist;
use Sadl\Framework\EventDispatcher\EventDispatcher;
use Sadl\Framework\Http\Event\ResponseEvent;
use Sadl\Framework\ServiceProvider\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * list of events and corresponding listeners
     * @var array
     */
    private array $listen = [
        ResponseEvent::class => [
            InternalErrorListener::class,
            ContentLengthListener::class
        ],
        PostPersist::class => [
        ]
    ];

    /**
     * @param \Sadl\Framework\EventDispatcher\EventDispatcher $eventDispatcher
     */
    public function __construct(private EventDispatcher $eventDispatcher)
    {
    }

    /**
     * @return void
     */
    public function register(): void
    {
        // loop over each event in the listen array
        foreach ($this->listen as $eventName => $listeners) {
            // loop over each listener and make sure that each listener just appears once
            foreach (array_unique($listeners) as $listener) {
                // call eventDispatcher->addListener
                $this->eventDispatcher->addListener($eventName, new $listener());
            }
        }
    }
}