<?php
/**
 * @author Oleg Efimov <o.efimov@gmail.com>
 */
namespace Badoo\SoftMock\Tests;

class WithReturnTypeDeclarationsPHP7TestClass
{
    public static function getString() : string
    {
        return "string";
    }
}
