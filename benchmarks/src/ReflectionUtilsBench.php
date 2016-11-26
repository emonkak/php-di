<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Eight;
use Emonkak\Di\Benchmarks\Fixtures\Five;
use Emonkak\Di\Benchmarks\Fixtures\Four;
use Emonkak\Di\Benchmarks\Fixtures\Functions;
use Emonkak\Di\Benchmarks\Fixtures\Nine;
use Emonkak\Di\Benchmarks\Fixtures\One;
use Emonkak\Di\Benchmarks\Fixtures\Seven;
use Emonkak\Di\Benchmarks\Fixtures\Six;
use Emonkak\Di\Benchmarks\Fixtures\Ten;
use Emonkak\Di\Benchmarks\Fixtures\Three;
use Emonkak\Di\Benchmarks\Fixtures\Two;
use Emonkak\Di\Benchmarks\Fixtures\Zero;
use Emonkak\Di\Utils\ReflectionUtils;

class ReflectionUtilsBench
{
    /**
     * @Groups({"new_instance"})
     */
    public function benchNewInstance()
    {
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Zero',  []);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\One',   [1]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Two',   [1, 2]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Three', [1, 2, 3]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Four',  [1, 2, 3, 4]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Five',  [1, 2, 3, 4, 5]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Six',   [1, 2, 3, 4, 5, 6]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Seven', [1, 2, 3, 4, 5, 6, 7]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Eight', [1, 2, 3, 4, 5, 6, 7, 8]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Nine',  [1, 2, 3, 4, 5, 6, 7, 8, 9]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Fixtures\Ten',   [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    }

    /**
     * @Groups({"new_instance"})
     */
    public function benchNewInstanceWithReflection()
    {
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Zero',  []);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\One',   [1]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Two',   [1, 2]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Three', [1, 2, 3]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Four',  [1, 2, 3, 4]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Five',  [1, 2, 3, 4, 5]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Six',   [1, 2, 3, 4, 5, 6]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Seven', [1, 2, 3, 4, 5, 6, 7]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Eight', [1, 2, 3, 4, 5, 6, 7, 8]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Nine',  [1, 2, 3, 4, 5, 6, 7, 8, 9]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Fixtures\Ten',   [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    }

    /**
     * @Groups({"call_method"})
     */
    public function benchCallMethod()
    {
        $f = new Functions();
        ReflectionUtils::callMethod($f, 'zero',  []);
        ReflectionUtils::callMethod($f, 'one',   [1]);
        ReflectionUtils::callMethod($f, 'two',   [1, 2]);
        ReflectionUtils::callMethod($f, 'three', [1, 2, 3]);
        ReflectionUtils::callMethod($f, 'four',  [1, 2, 3, 4]);
        ReflectionUtils::callMethod($f, 'five',  [1, 2, 3, 4, 5]);
        ReflectionUtils::callMethod($f, 'six',   [1, 2, 3, 4, 5, 6]);
        ReflectionUtils::callMethod($f, 'seven', [1, 2, 3, 4, 5, 6, 7]);
        ReflectionUtils::callMethod($f, 'eight', [1, 2, 3, 4, 5, 6, 7, 8]);
        ReflectionUtils::callMethod($f, 'nine',  [1, 2, 3, 4, 5, 6, 7, 8, 9]);
        ReflectionUtils::callMethod($f, 'ten',   [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    }

    /**
     * @Groups({"call_method"})
     */
    public function benchCallMethodWithReflection()
    {
        $f = new Functions();
        ReflectionUtils::callMethodWithReflection($f, 'zero',  []);
        ReflectionUtils::callMethodWithReflection($f, 'one',   [1]);
        ReflectionUtils::callMethodWithReflection($f, 'two',   [1, 2]);
        ReflectionUtils::callMethodWithReflection($f, 'three', [1, 2, 3]);
        ReflectionUtils::callMethodWithReflection($f, 'four',  [1, 2, 3, 4]);
        ReflectionUtils::callMethodWithReflection($f, 'five',  [1, 2, 3, 4, 5]);
        ReflectionUtils::callMethodWithReflection($f, 'six',   [1, 2, 3, 4, 5, 6]);
        ReflectionUtils::callMethodWithReflection($f, 'seven', [1, 2, 3, 4, 5, 6, 7]);
        ReflectionUtils::callMethodWithReflection($f, 'eight', [1, 2, 3, 4, 5, 6, 7, 8]);
        ReflectionUtils::callMethodWithReflection($f, 'nine',  [1, 2, 3, 4, 5, 6, 7, 8, 9]);
        ReflectionUtils::callMethodWithReflection($f, 'ten',   [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    }
}
