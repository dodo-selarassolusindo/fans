<?php

namespace PHPMaker2024\prj_fans;

use Symfony\Contracts\EventDispatcher\Event;
use DI\ContainerBuilder;

/**
 * Container Build Event
 */
class ContainerBuildEvent extends Event
{
    public const NAME = "container.build";

    public function __construct(protected ContainerBuilder $builder)
    {
    }

    public function getBuilder(): ContainerBuilder
    {
        return $this->builder;
    }

    public function getSubject(): ContainerBuilder
    {
        return $this->builder;
    }
}
