<?php

namespace Emonkak\Di\Benchmarks\Utils;

use Athletic\AthleticEvent;
use Emonkak\Di\Utils\ReflectionUtils;

class ReflectionUtilsEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function newInstance()
    {
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Zero',  []);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\One',   [1]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Two',   [1, 2]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Three', [1, 2, 3]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Four',  [1, 2, 3, 4]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Five',  [1, 2, 3, 4, 5]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Six',   [1, 2, 3, 4, 5, 6]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Seven', [1, 2, 3, 4, 5, 6, 7]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Eight', [1, 2, 3, 4, 5, 6, 7, 8]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Nine',  [1, 2, 3, 4, 5, 6, 7, 8, 9]);
        ReflectionUtils::newInstance('Emonkak\Di\Benchmarks\Utils\Ten',   [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    }

    /**
     * @iterations 1000
     */
    public function newInstanceWithReflection()
    {
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Zero',  []);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\One',   [1]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Two',   [1, 2]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Three', [1, 2, 3]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Four',  [1, 2, 3, 4]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Five',  [1, 2, 3, 4, 5]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Six',   [1, 2, 3, 4, 5, 6]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Seven', [1, 2, 3, 4, 5, 6, 7]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Eight', [1, 2, 3, 4, 5, 6, 7, 8]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Nine',  [1, 2, 3, 4, 5, 6, 7, 8, 9]);
        ReflectionUtils::newInstanceWithReflection('Emonkak\Di\Benchmarks\Utils\Ten',   [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    }

    /**
     * @iterations 1000
     */
    public function callMethod()
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
     * @iterations 1000
     */
    public function callMethodWithReflection()
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

class Functions
{
    public function zero()
    {
    }

    public function one($a)
    {
    }

    public function two($a, $b)
    {
    }

    public function three($a, $b, $c)
    {
    }

    public function four($a, $b, $c, $d)
    {
    }

    public function five($a, $b, $c, $d, $e)
    {
    }

    public function six($a, $b, $c, $d, $e, $f)
    {
    }

    public function seven($a, $b, $c, $d, $e, $f, $g)
    {
    }

    public function eight($a, $b, $c, $d, $e, $f, $g, $h)
    {
    }

    public function nine($a, $b, $c, $d, $e, $f, $g, $h, $i)
    {
    }

    public function ten($a, $b, $c, $d, $e, $f, $g, $h, $i, $j)
    {
    }
}

class Zero
{
    public function __construct()
    {
    }
}

class One
{
    public function __construct($a)
    {
    }
}

class Two
{
    public function __construct($a, $b)
    {
    }
}

class Three
{
    public function __construct($a, $b, $c)
    {
    }
}

class Four
{
    public function __construct($a, $b, $c, $d)
    {
    }
}

class Five
{
    public function __construct($a, $b, $c, $d, $e)
    {
    }
}

class Six
{
    public function __construct($a, $b, $c, $d, $e, $f)
    {
    }
}

class Seven
{
    public function __construct($a, $b, $c, $d, $e, $f, $g)
    {
    }
}

class Eight
{
    public function __construct($a, $b, $c, $d, $e, $f, $g, $h)
    {
    }
}

class Nine
{
    public function __construct($a, $b, $c, $d, $e, $f, $g, $h, $i)
    {
    }
}

class Ten
{
    public function __construct($a, $b, $c, $d, $e, $f, $g, $h, $i, $j)
    {
    }
}
