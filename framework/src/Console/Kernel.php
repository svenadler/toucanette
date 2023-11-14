<?php declare(strict_types=1);

namespace Sadl\Framework\Console;

use DirectoryIterator;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Sadl\Framework\Console\Command\CommandInterface;

final class Kernel
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param \Sadl\Framework\Console\Application $application
     */
    public function __construct(
        private ContainerInterface $container,
        private Application $application
    )
    {
    }

    /**
     * @return int
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     * @throws \Sadl\Framework\Console\Exception\ConsoleException
     */
    public function handle(): int
    {
        $this->registerCommands();

        $status = $this->application->run();

        return $status;
    }

    /**
     * @throws \ReflectionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function registerCommands(): void
    {
        $commandFiles = new DirectoryIterator(__DIR__ . '/Command');

        $namespace = $this->container->get('base-commands-namespace');

        // Loop over all files in the commands folder
        foreach ($commandFiles as $commandFile) {

            if (!$commandFile->isFile()) {
                continue;
            }

            // Get the Command class name..using psr4 this will be same as filename
            $command = $namespace . pathinfo($commandFile->getFileName(), PATHINFO_FILENAME);

            // If it is a subclass of CommandInterface
            if (is_subclass_of($command, CommandInterface::class)) {
                // Add to the container, using the name as the ID e.g. $container->add('database:migrations:migrate', MigrateDatabase::class)
                $commandName = (new ReflectionClass($command))->getProperty('name')->getDefaultValue();

                $this->container->add($commandName, $command);
            }
        }
    }
}