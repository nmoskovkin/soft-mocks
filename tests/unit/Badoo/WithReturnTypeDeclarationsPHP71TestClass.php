<?php
/**
 * @author Oleg Efimov <o.efimov@gmail.com>
 */
namespace Badoo\SoftMock\Tests;

class WithReturnTypeDeclarationsPHP71TestClass
{
    public static function getStringOrNull(?int $int) : ?string
    {
        return null;
    }
}
