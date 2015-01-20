<?php

namespace Emonkak\Di;

interface ContainerConfiguratorInterface
{
    /**
     * @param Container $container
     */
    public function configure(Container $container);
}
