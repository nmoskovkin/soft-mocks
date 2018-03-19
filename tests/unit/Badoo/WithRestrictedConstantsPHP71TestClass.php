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
}

function getPrivateValue() : int
{
    return WithRestrictedConstantsPHP71TestClass::PRIVATE_VALUE;
}

function getProtectedValue() : int
{
    return WithRestrictedConstantsPHP71TestClass::PROTECTED_VALUE;
}

class CrossBasePHP71TestClass {}

class CrossFirstPHP71TestClass extends CrossBasePHP71TestClass
{
    protected const CROSS = 10;
}

class CrossSecondPHP71TestClass extends CrossBasePHP71TestClass
{
    public static function getCross()
    {
        return CrossFirstPHP71TestClass::CROSS;
    }
}

class DescendantBasePHP71TestClass
{
    public static function getDescendant()
    {
        return static::DESCENDANT;
    }
}

class DescendantFirstPHP71TestClass extends DescendantBasePHP71TestClass
{
    protected const DESCENDANT = 20;
}

class ConstantRedeclareBasePHP71TestClass
{
    public static function getBaseSelfValue() : int
    {
        return self::VALUE;
    }

    public static function getBaseStaticValue() : int
    {
        return static::VALUE;
    }
}

class ConstantRedeclareFirstPHP71TestClass extends ConstantRedeclareBasePHP71TestClass
{
    protected const VALUE = 2;

    public static function getFirstParentValue() : int
    {
        return parent::VALUE;
    }

    public static function getFirstSelfValue() : int
    {
        return self::VALUE;
    }

    public static function getFirstStaticValue() : int
    {
        return static::VALUE;
    }
}

class ConstantRedeclareSecondPHP71TestClass extends ConstantRedeclareFirstPHP71TestClass
{
    public static function getSecondParentValue() : int
    {
        return parent::VALUE;
    }

    public static function getSecondSelfValue() : int
    {
        return self::VALUE;
    }

    public static function getSecondStaticValue() : int
    {
        return static::VALUE;
    }
}

class ConstantRedeclareThirdPHP71TestClass extends ConstantRedeclareSecondPHP71TestClass
{
    protected const VALUE = 4;

    public static function getThirdParentValue() : int
    {
        return parent::VALUE;
    }

    public static function getThirdSelfValue() : int
    {
        return self::VALUE;
    }

    public static function getThirdStaticValue() : int
    {
        return static::VALUE;
    }
}

class ConstantRedeclareForthPHP71TestClass extends ConstantRedeclareThirdPHP71TestClass
{
    public static function getForthParentValue() : int
    {
        return parent::VALUE;
    }

    public static function getForthSelfValue() : int
    {
        return self::VALUE;
    }

    public static function getForthStaticValue() : int
    {
        return static::VALUE;
    }
}
