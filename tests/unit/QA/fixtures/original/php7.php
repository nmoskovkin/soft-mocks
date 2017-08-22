<?php
/**
 * This file contains php7 code
 */

function replaceSomething($string) : string
{
    // Comment
    /* Comment */
    return str_replace('something', 'somebody', $string);
}

class SomeClass
{
    public $a = 1;

    public function method($string) : string
    {
        return self::methodSelf($string);
    }

    protected static function methodSelf($string) : string
    {
        return replaceSomething($string);
    }
}
