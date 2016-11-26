<?php

namespace Emonkak\Di\Tests\InjectionPolicy\Stubs;

use Emonkak\Di\Annotation\Inject;
use Emonkak\Di\Annotation\Scope;

/**
 * @Inject
 * @Scope(Scope::PROTOTYPE)
 */
class Bar
{
    private function __construct() {}
}
