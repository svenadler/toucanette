<?php

namespace Sadl\Framework\Console\Command;

class MigrateDatabase implements CommandInterface
{
    /**
     * @var string
     */
    public string $name = 'database:migrations:migrate';

    /**
     * @param array $params
     *
     * @return int
     */
    public function execute(array $params = []): int
    {
        echo 'Executing MigrateDatabase command' . PHP_EOL;

        return 0;
    }
}