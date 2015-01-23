<?php

namespace Emonkak\Di;

interface ContainerConfiguratorInterface
{
    /**
     * @param ContainerInterface $container
     */
    public function configure(ContainerInterface $container);
}
