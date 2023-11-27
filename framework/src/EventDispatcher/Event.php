<?php declare(strict_types=1);

namespace Sadl\Framework\EventDispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class Event implements StoppableEventInterface
{
    /**
     * @var bool
     */
    private bool $propagationStopped = false;

    /**
     * @return bool
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    /**
     * @return void
     */
    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }
}