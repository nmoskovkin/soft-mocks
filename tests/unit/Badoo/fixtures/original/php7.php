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

    public function methodReturn() : string
    {
        return self::methodSelf("string");
    }

    protected static function methodSelf($string) : string
    {
        return replaceSomething($string);
    }

    public function methodParam(string $string)
    {
        return $string;
    }

    public function methodNullableParam(?string $string)
    {
        return $string;
    }

    public function methodNullableReturn() : ?array
    {
        return null;
    }

    public function methodVoidReturn() : void
    {
        echo "something";
    }

    public function methodNullableParamReturn(?string $string) : string
    {
        return $string ?? "string";
    }

    public function methodParamNullableReturn(string $string) : ?string
    {
        return $string ? $string : null;
    }

    public function methodNullableParamNullableReturn(?string $string) : ?string
    {
        return $string;
    }

    public function methodWithOnlyVariadicParams( ...$args)
    {
        return sizeof($args);
    }

    public function methodWithDifferentParamsTypes($a, $b, ...$args)
    {
        return $a . $b . sizeof($args);
    }
}
