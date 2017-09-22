<?php
/**
 * @author Oleg Efimov <o.efimov@gmail.com>
 */
namespace Badoo\SoftMock\Tests;

class WithReturnTypeDeclarationsPHP71TestClass
{
    public static function getStringOrNull() : ?string
    {
        return null;
    }
}
