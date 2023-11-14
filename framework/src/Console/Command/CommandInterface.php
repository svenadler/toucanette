<?php declare(strict_types=1);

namespace Sadl\Framework\Console\Command;

interface CommandInterface
{
    /**
     * @param array $params
     *
     * @return int
     */
    public function execute(array $params = []): int;
}