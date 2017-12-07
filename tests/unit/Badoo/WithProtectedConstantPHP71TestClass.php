<?php
/**
 * @author Oleg Efimov <o.efimov@corp.badoo.com>
 * @author Kirill Abrosimov <k.abrosimov@corp.badoo.com>
 */
namespace Badoo\SoftMock\Tests;

class WithProtectedConstantPHP71TestClass
{
    protected const VALUE = 1;

    public static function getValue()
    {
        return self::VALUE;
    }

    public function getObjectValue()
    {
        return self::VALUE;
    }
}
