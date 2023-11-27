<?php declare(strict_types=1);

use App\Provider\EventServiceProvider;

function bootstrap($container): void
{
    $providers = [
        EventServiceProvider::class
    ];

    foreach ($providers as $providerClass) {
        $provider = $container->get($providerClass);
        $provider->register();
    }
}