<?php

namespace Emonkak\Di;

interface ContainerConfiguratorInterface
{
    /**
     * @param AbstractContainerInterface $container
     */
    public function configure(AbstractContainerInterface $container);
}
