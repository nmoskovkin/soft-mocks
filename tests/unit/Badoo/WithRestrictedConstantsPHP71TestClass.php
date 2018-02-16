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

    public static function getPrivateValue() : int
    {
        return self::PRIVATE_VALUE;
    }

    public static function getSelfProtectedValue() : int
    {
        return self::PROTECTED_VALUE;
    }

    public static function getStaticProtectedValue() : int
    {
        return static::PROTECTED_VALUE;
    }

    public function getThisObjectProtectedValue() : int
    {
        return $this::PROTECTED_VALUE;
    }
}

class WithWrongPrivateConstantAccessPHP71TestClass
{
    public static function getPrivateValue() : int
    {
        return WithRestrictedConstantsPHP71TestClass::PRIVATE_VALUE;
    }
}

class WithWrongProtectedConstantAccessPHP71TestClass
{
    public static function getProtectedValue() : int
    {
        return WithRestrictedConstantsPHP71TestClass::PROTECTED_VALUE;
    }
}

class WithRestrictedConstantsChildPHP71TestClass extends WithRestrictedConstantsPHP71TestClass
{
    public static function getParentPrivateValue() : int
    {
        return parent::PRIVATE_VALUE;
    }

    public static function getParentProtectedValue() : int
    {
        return parent::PROTECTED_VALUE;
    }
}

function getPrivateValue() : int
{
    return WithRestrictedConstantsPHP71TestClass::PRIVATE_VALUE;
}

function getProtectedValue() : int
{
    return WithRestrictedConstantsPHP71TestClass::PROTECTED_VALUE;
}

class CrossBase {}

class CrossFirst extends CrossBase
{
    protected const CROSS = 10;
}

class CrossSecond extends CrossBase
{
    public static function getCross()
    {
        return CrossFirst::CROSS;
    }
}

class DescendantBase
{
    public static function getDescendant()
    {
        return static::DESCENDANT;
    }
}

class DescendantFirst extends DescendantBase
{
    protected const DESCENDANT = 20;
}
