<?php
/**
 * This file contains php71 code
 */
class SomeClass
{
    protected const VALUES = ['a', 'b', 'c'];

    public static function getAB()
    {
        [
            $a,,
            $b
        ] = self::VALUES;

        return [$a, $b];
    }
}
