<?php declare(strict_types=1);

namespace Sadl\Framework\Authentication;

interface AuthRepositoryInterface
{
    /**
     * @param string $username
     *
     * @return \Sadl\Framework\Authentication\AuthUserInterface|null
     */
    public function findByUsername(string $username): ?AuthUserInterface;
}