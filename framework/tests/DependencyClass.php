<?php declare(strict_types=1);

namespace Sadl\Framework\Tests;

class DependencyClass
{
    /**
     * @param \Sadl\Framework\Tests\SubDependencyClass $subDependency
     */
    public function __construct(private SubDependencyClass $subDependency)
    {
    }

    /**
     * @return \Sadl\Framework\Tests\SubDependencyClass
     */
    public function getSubDependency(): SubDependencyClass
    {
        return $this->subDependency;
    }
}