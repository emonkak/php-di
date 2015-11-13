<?php

namespace Emonkak\Di;

interface ContainerConfiguratorInterface
{
    /**
     * @param AbstractContainer $container
     */
    public function configure(AbstractContainer $container);
}
