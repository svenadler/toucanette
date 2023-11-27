<?php declare(strict_types=1);

namespace Sadl\Framework\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var iterable|array
     */
    private iterable $listeners = [];

    /**
     * @inheritDoc
     */
    public function dispatch(object $event): object
    {
        // Loop over the listeners for the event
        foreach ($this->getListenersForEvent($event) as $listener) {
            // Break if propagation stopped
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event;
            }

            // Call the listener, passing in the event (each listener will be a callable)
            $listener($event);
        }

        return $event;
    }

    /**
     * @param string $eventName // $eventName e.g. Framework\EventDispatcher\ResponseEvent
     * @param callable $listener
     *
     * @return $this
     */
    public function addListener(string $eventName, callable $listener): self
    {
        $this->listeners[$eventName][] = $listener;

        return $this;
    }

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventName = get_class($event);

        if (array_key_exists($eventName, $this->listeners)) {
            return $this->listeners[$eventName];
        }

        return [];
    }
}