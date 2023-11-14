<?php declare(strict_types=1);

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(BASE_PATH . '/.env');

$container = new League\Container\Container();

$container->delegate(new \League\Container\ReflectionContainer(true));

# parameters for application config
$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_SERVER['APP_ENV'];
$templatesPath = BASE_PATH . '/templates';

$container->add('APP_ENV', new \League\Container\Argument\Literal\StringArgument($appEnv));
$databaseUrl = 'sqlite:///' . BASE_PATH . '/var/db.sqlite';

$container->add(
    'base-commands-namespace',
    new \League\Container\Argument\Literal\StringArgument('Sadl\\Framework\\Console\\Command\\')
);

# services
$container->add(
    Sadl\Framework\Routing\RouterInterface::class,
    Sadl\Framework\Routing\Router::class
);

$container->extend(Sadl\Framework\Routing\RouterInterface::class)
    ->addMethodCall(
        'setRoutes',
        [new \League\Container\Argument\Literal\ArrayArgument($routes)]
    );

$container->add(Sadl\Framework\Http\Kernel::class)
    ->addArgument(Sadl\Framework\Routing\RouterInterface::class)
    ->addArgument($container);

$container->add(\Sadl\Framework\Console\Application::class)->addArgument($container);

$container->add(\Sadl\Framework\Console\Kernel::class)
    ->addArguments([$container, \Sadl\Framework\Console\Application::class]);

$container->addShared('filesystem-loader', \Twig\Loader\FilesystemLoader::class)
    ->addArgument(new \League\Container\Argument\Literal\StringArgument($templatesPath));

$container->addShared('twig', \Twig\Environment::class)
    ->addArgument('filesystem-loader');

$container->add(\Sadl\Framework\Controller\AbstractController::class);

$container->inflector(\Sadl\Framework\Controller\AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(\Sadl\Framework\Dbal\ConnectionFactory::class)
    ->addArguments([
        new \League\Container\Argument\Literal\StringArgument($databaseUrl)
    ]);

$container->addShared(\Doctrine\DBAL\Connection::class, function () use ($container): \Doctrine\DBAL\Connection {
    return $container->get(\Sadl\Framework\Dbal\ConnectionFactory::class)->create();
});

return $container;
