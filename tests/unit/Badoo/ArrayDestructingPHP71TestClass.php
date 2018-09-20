<?php
/**
 * @author Oleg Efimov <o.efimov@corp.badoo.com>
 * @author Kirill Abrosimov <k.abrosimov@corp.badoo.com>
 */
namespace Badoo\SoftMock\Tests;

class ArrayDestructingPHP71TestClass
{
    protected const VALUES = ['a', 'b', 'c'];

    public static function getA()
    {
        [$a,,] = self::VALUES;
        return $a;
    }

    public static function getB()
    {
        [, $b,] = self::VALUES;
        return $b;
    }

    public static function getC()
    {
        [,, $c] = self::VALUES;
        return $c;
    }
}
