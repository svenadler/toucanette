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
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Sadl\Framework\Console\Exception\ConsoleException
     */
    public function run(): int
    {
        // Use environment variables to obtain the command name
        $argv = $_SERVER['argv'];

        // [0] => bin/console [1] => command name e.g. database:migrations:migrate
        $commandName = $argv[1] ?? null;

        // Throw an exception if no command name is provided
        if (!$commandName) {
            throw new ConsoleException('A command name must be provided');
        }

        // Use command name to obtain a command object from the container
        $command = $this->container->get($commandName);

        // Parse variables to obtain options and args
        $args = array_slice($argv,2);

        $options = $this->parseOptions($args);

        // Execute the command, returning the status code
        $status = $command->execute($options);

        // Return the status code
        return $status;
    }

    /**
     * @param array $args
     *
     * @return array
     */
    private function parseOptions(array $args): array
    {
        $options = [];

        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {
                // This is an option e.g. --up=1 --> [0] => up [1] = 1: --up=1 = up => 1, --foo = foo => true
                $option = explode('=', substr($arg, 2));
                // index 0 is key and 1 is the value
                $options[$option[0]] = $option[1] ?? true;
            }
        }

        return $options;
    }
}