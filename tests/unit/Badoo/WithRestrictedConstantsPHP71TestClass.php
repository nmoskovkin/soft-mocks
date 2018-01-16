<?php
/**
 * @author Oleg Efimov <o.efimov@corp.badoo.com>
 * @author Kirill Abrosimov <k.abrosimov@corp.badoo.com>
 */
namespace Badoo\SoftMock\Tests;

class WithRestrictedConstantsPHP71TestClass
{
    private const PRIVATE_VALUE = 1;
    protected const PROTECTED_VALUE = 11;

    public static function getPrivateValue()
    {
        return self::PRIVATE_VALUE;
    }

    public function getObjectPrivateValue()
    {
        return self::PRIVATE_VALUE;
    }

    public static function getProtectedValue()
    {
        return self::PROTECTED_VALUE;
    }

    public function getObjectProtectedValue()
    {
        return self::PROTECTED_VALUE;
    }
}

class WithWrongPrivateConstantAccessPHP71TestClass
{
    public static function getPrivateValue()
    {
        return WithRestrictedConstantsPHP71TestClass::PRIVATE_VALUE;
    }
}

class WithWrongProtectedConstantAccessPHP71TestClass
{
    public static function getProtectedValue()
    {
        return WithRestrictedConstantsPHP71TestClass::PROTECTED_VALUE;
    }
}

class WithRestrictedConstantsChildPHP71TestClass extends WithRestrictedConstantsPHP71TestClass
{
    public static function getParentPrivateValue()
    {
        return parent::PRIVATE_VALUE;
    }

    public static function getParentProtectedValue()
    {
        return parent::PROTECTED_VALUE;
    }
}

function getPrivateValue()
{
    return WithRestrictedConstantsPHP71TestClass::PRIVATE_VALUE;
}

function getProtectedValue()
{
    return WithRestrictedConstantsPHP71TestClass::PROTECTED_VALUE;
}