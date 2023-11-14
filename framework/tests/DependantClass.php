<?php declare(strict_types=1);

namespace Sadl\Framework\Tests;

readonly class DependantClass
{
    /**
     * @param \Sadl\Framework\Tests\DependencyClass $dependency
     */
    public function __construct(private DependencyClass $dependency)
    {
    }

    /**
     * @return \Sadl\Framework\Tests\DependencyClass
     */
    public function getDependency(): DependencyClass
    {
        return $this->dependency;
    }
}