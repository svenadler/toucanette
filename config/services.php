<?php declare(strict_types=1);

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

$container = new League\Container\Container();

$container->delegate(new \League\Container\ReflectionContainer(true));

###########################################
#### parameters for application config ####
###########################################
$basePath = dirname(__DIR__);
$container->add('basePath', new \League\Container\Argument\Literal\StringArgument($basePath));
$routes = include $basePath . '/routes/web.php';
$appEnv = $_SERVER['APP_ENV'];
$templatesPath = $basePath . '/templates';

$container->add('APP_ENV', new \League\Container\Argument\Literal\StringArgument($appEnv));
$databaseUrl = 'sqlite:///' . $basePath . '/var/db.sqlite';

$container->add(
    'base-commands-namespace',
    new \League\Container\Argument\Literal\StringArgument('Sadl\\Framework\\Console\\Command\\')
);

##################
#### services ####
##################
$container->add(
    Sadl\Framework\Routing\RouterInterface::class,
    Sadl\Framework\Routing\Router::class
);

$container->add(
    \Sadl\Framework\Http\Middleware\RequestHandlerInterface::class,
    \Sadl\Framework\Http\Middleware\RequestHandler::class
)->addArgument($container);

$container->addShared(\Sadl\Framework\EventDispatcher\EventDispatcher::class);

$container->add(Sadl\Framework\Http\Kernel::class)
    ->addArguments([
        $container,
        \Sadl\Framework\Http\Middleware\RequestHandlerInterface::class,
        \Sadl\Framework\EventDispatcher\EventDispatcher::class
    ]);

# Console #
$container->add(\Sadl\Framework\Console\Application::class)->addArgument($container);

$container->add(\Sadl\Framework\Console\Kernel::class)
    ->addArguments([$container, \Sadl\Framework\Console\Application::class]);

## Session ##
$container->addShared(
    \Sadl\Framework\Session\SessionInterface::class,
    \Sadl\Framework\Session\Session::class
);

## Twig ##
$container->add('template-renderer-factory', \Sadl\Framework\Template\TwigFactory::class)
    ->addArguments([
            \Sadl\Framework\Session\SessionInterface::class,
            new \League\Container\Argument\Literal\StringArgument($templatesPath)
        ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('template-renderer-factory')->create();
});

## Controller ##
$container->add(\Sadl\Framework\Controller\AbstractController::class);

$container->inflector(\Sadl\Framework\Controller\AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

## Dbal ##
$container->add(\Sadl\Framework\Dbal\ConnectionFactory::class)
    ->addArguments([
        new \League\Container\Argument\Literal\StringArgument($databaseUrl)
    ]);

$container->addShared(\Doctrine\DBAL\Connection::class, function () use ($container): \Doctrine\DBAL\Connection {
    return $container->get(\Sadl\Framework\Dbal\ConnectionFactory::class)->create();
});

$container->add(
    'database:migrations:migrate',
    \Sadl\Framework\Console\Command\MigrateDatabase::class
)->addArguments([
    \Doctrine\DBAL\Connection::class,
    new \League\Container\Argument\Literal\StringArgument($basePath . '/migrations')
]);

# Middleware #
$container->add(\Sadl\Framework\Http\Middleware\RouterDispatch::class)
    ->addArguments([
        \Sadl\Framework\Routing\RouterInterface::class,
        $container
    ]);

$container->add(\Sadl\Framework\Authentication\SessionAuthentication::class)
    ->addArguments([
        \App\Repository\User\UserRepository::class,
        \Sadl\Framework\Session\SessionInterface::class
    ]);

$container->add(\Sadl\Framework\Http\Middleware\ExtractRouteInfo::class)
    ->addArgument(new \League\Container\Argument\Literal\ArrayArgument($routes));

return $container;
