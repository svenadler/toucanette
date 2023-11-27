<?php declare(strict_types=1);

namespace App\EventListener;

use Sadl\Framework\Http\Event\ResponseEvent;

class InternalErrorListener
{
    /**
     * @var int
     */
    private const INTERNAL_ERROR_MIN_VALUE = 499;

    /**
     * @param \Sadl\Framework\Http\Event\ResponseEvent $event
     *
     * @return void
     */
    public function __invoke(ResponseEvent $event): void
    {
        $status = $event->getResponse()->getStatus();

        if ($status > self::INTERNAL_ERROR_MIN_VALUE) {
            $event->stopPropagation(); // set propagationStopped to true and the EventDispatcher returns the event and no more listeners will be called
        }
//        dd('propagation stopped');
    }
}