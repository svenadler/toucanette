<?php declare(strict_types=1);

namespace Sadl\Framework\ServiceProvider;

interface ServiceProviderInterface
{
    /**
     * @return void
     */
    public function register(): void;
}