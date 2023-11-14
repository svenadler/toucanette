<?php

namespace Sadl\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    /**
     * @param string $databaseUrl
     */
    public function __construct(private string $databaseUrl)
    {
    }

    /**
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\Exception
     */
    public function create(): Connection
    {
        return DriverManager::getConnection(['url' => $this->databaseUrl]);
    }
}