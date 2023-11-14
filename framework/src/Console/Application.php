<?php declare(strict_types=1);

namespace Sadl\Framework\Console;

use Psr\Container\ContainerInterface;
use Sadl\Framework\Console\Exception\ConsoleException;

class Application
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @return int
     */
    public function run(): int
    {
        // Use environment variables to obtain the command name
        $argv = $_SERVER['argv'];

//        dd($argv);

        $commandName = $argv[1] ?? null;

        // Throw an exception if no command name is provided
        if (!$commandName) {
            throw new ConsoleException('A command name must be provided');
        }

        // Use command name to obtain a command object from the container
        $command = $this->container->get($commandName);

        // Parse variables to obtain options and args

        // Execute the command, returning the status code
        $status = $command->execute();

        // Return the status code
        return $status;
    }
}