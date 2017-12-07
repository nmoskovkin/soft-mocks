<?php
/**
 * Mocks core that rewrites code
 * @author Kirill Abrosimov <k.abrosimov@corp.badoo.com>
 * @author Oleg Efimov <o.efimov@corp.badoo.com>
 * @author Rinat Akhmadeev <r.akhmadeev@corp.badoo.com>
 */
namespace Badoo\SoftMock\Tests;

class SoftMocksTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        require_once __DIR__ . '/SoftMocksTestClasses.php';
    }

    protected function tearDown()
    {
        \Badoo\SoftMocks::restoreAll();
        parent::tearDown();
    }

    public static function markTestSkippedForPHPVersionBelow($php_version)
    {
        if (version_compare(phpversion(), $php_version, '<')) {
            static::markTestSkipped('You PHP version do not support this, you need need at least PHP ' . $php_version);
        }
    }

    public function testParserVersion()
    {
        $composer_json = file_get_contents(__DIR__ . '/../../../composer.json');
        static::assertNotEmpty($composer_json, "Can't get content from composer.json file");
        $composer_data = json_decode($composer_json, true);
        static::assertSame(
            JSON_ERROR_NONE,
            json_last_error(),
            "Can't parse composer.json: [" . json_last_error() . '] ' . json_last_error_msg()
        );
        static::assertSame(\Badoo\SoftMocks::PARSER_VERSION, $composer_data['require']['nikic/php-parser']);
    }

    public function testExitMock()
    {
        \Badoo\SoftMocks::redefineExit(
            '',
            function ($code) {
                throw new \Exception("exit called: {$code}");
            }
        );
        try {
            $result = WithExitTestClass::doWithExit();
            $this->fail('exception expected');
        } catch (\Exception $Exception) {
            static::assertEquals('exit called: ' . (WithExitTestClass::RESULT * 10), $Exception->getMessage());
            $result = false;
        }
        static::assertFalse($result);

        \Badoo\SoftMocks::restoreExit();

        \Badoo\SoftMocks::redefineExit(
            '',
            function ($code) {
                WithExitTestClass::$exit_code = $code;
                WithExitTestClass::$exit_called = true;
            }
        );
        $result = WithExitTestClass::doWithExit();
        static::assertEquals(WithExitTestClass::RESULT, $result);
        static::assertEquals(WithExitTestClass::RESULT * 10, WithExitTestClass::$exit_code);
        static::assertTrue(WithExitTestClass::$exit_called);
        \Badoo\SoftMocks::restoreExit();
    }

    public function testRedefineConstructor()
    {
        \Badoo\SoftMocks::redefineMethod(
            ConstructTestClass::class,
            '__construct',
            '',
            '$this->constructor_params = $mm_func_args;'
        );
        $object = new ConstructTestClass(1, 2, 3);
        static::assertEquals(42, $object->doSomething());
        static::assertEquals([1, 2, 3], $object->getConstructorParams());
    }

    public function testRedefineMethod()
    {
        \Badoo\SoftMocks::redefineMethod(
            BaseInheritanceTestClass::class,
            'doSomething',
            '',
            'return 43;'
        );
        $a = new BaseInheritanceTestClass();
        static::assertEquals(43, $a->doSomething());
    }

    public function testRedefineMethodWithInheritedClasses()
    {
        \Badoo\SoftMocks::redefineMethod(
            BaseInheritanceTestClass::class,
            'doSomething',
            '',
            'return 43;'
        );
        $a = new InheritanceTestClass();
        static::assertEquals(43, $a->doSomething());
    }

    public function testRedefineMethodWithInheritedClasses2()
    {
        \Badoo\SoftMocks::redefineMethod(
            InheritanceTestClass::class,
            'doSomething',
            '',
            'return 43;'
        );
        $a = new InheritanceTestClass();
        static::assertEquals(43, $a->doSomething());
    }

    public function testParentMismatch()
    {
        \Badoo\SoftMocks::redefineMethod(
            ParentMismatchChildTestClass::class,
            'f',
            '$c',
            'return \Badoo\SoftMocks::callOriginal([\Badoo\SoftMock\Tests\ParentMismatchChildTestClass::class, "f"], [$c]) + 1;'
        );
        \Badoo\SoftMocks::redefineMethod(ParentMismatchBaseTestClass::class, 'f', '', 'return 100;');

        static::assertEquals(2, ParentMismatchChildTestClass::f(true));
        static::assertEquals(101, ParentMismatchChildTestClass::f(false));
    }

    public function testGenerators()
    {
        $Generators = new GeneratorsTestClass();
        $values = [];
        \Badoo\SoftMocks::redefineGenerator(
            GeneratorsTestClass::class,
            'yieldAb',
            [$this, 'yieldAbMock']
        );

        foreach ($Generators->yieldAb(1) as $value) {
            $values[] = $value;
        }

        static::assertEquals(
            array_values(range(0, 9)),
            $values
        );
    }

    public function yieldAbMock()
    {
        for ($i = 0; $i <= 9; $i++) {
            yield $i;
        }
    }

    public function testGeneratorsRef()
    {
        $Generators = new GeneratorsTestClass();
        $values = [];

        \Badoo\SoftMocks::redefineGenerator(
            GeneratorsTestClass::class,
            'yieldRef',
            [$this, 'yieldRefMock']
        );

        foreach ($Generators->yieldRef(10) as $value) {
            $value++;
            $values[] = $value;
        }
        unset($value);

        static::assertEquals(
            array_values(range(1, 10)),
            $values
        );

        $values = [];

        foreach ($Generators->yieldRef(10) as &$value) {
            $value++;
            $values[] = $value;
        }
        unset($value);

        static::assertEquals(
            array_values(range(1, 9, 2)),
            $values
        );
    }

    public function &yieldRefMock($num)
    {
        for ($i = 0; $i < $num; $i++) {
            yield $i;
        }
    }

    public function testDefault()
    {
        $Misc = new DefaultTestClass();
        static::assertTrue($Misc->doSomething(1, 2));

        \Badoo\SoftMocks::redefineMethod(
            DefaultTestClass::class,
            'doSomething',
            '$a, $b = 3',
            'return [$a, $b];'
        );

        static::assertEquals([1, 2], $Misc->doSomething(1, 2));
        static::assertEquals([1, 3], $Misc->doSomething(1));
    }

    public function testConstants()
    {
        static::assertEquals(1, DefaultTestClass::getValue());

        \Badoo\SoftMocks::redefineConstant('\Badoo\SoftMock\Tests\DefaultTestClass::VALUE', 2);

        static::assertEquals(2, DefaultTestClass::getValue());
    }

    public function testInheritMock()
    {
        \Badoo\SoftMocks::redefineMethod(
            EmptyTestClass::class,
            'getter',
            '',
            'return 20;'
        );
        $Child = new EmptyEmptyTestClass();
        static::assertSame(20, $Child->getter());
        $Test = new EmptyTestClass();
        static::assertSame(20, $Test->getter());
        $Parent = new BaseTestClass();
        static::assertSame(10, $Parent->getter());
    }

    public function testInheritTrapMock()
    {
        \Badoo\SoftMocks::redefineMethod(
            BaseTestClass::class,
            'getter',
            '',
            'return 30;'
        );
        $Child = new ReplacingParentTestClass();
        static::assertEquals(20, $Child->getter());

        $Parent = new BaseTestClass();
        static::assertEquals(30, $Parent->getter());
    }

    public function testInheritParentMock()
    {
        \Badoo\SoftMocks::redefineMethod(
            BaseTestClass::class,
            'getter',
            '',
            'return 7;'
        );
        $Child = new EmptyParentTestClass();
        $res = $Child->getter();
        static::assertSame(14, $res);
    }

    public function testInheritStaticMock()
    {
        \Badoo\SoftMocks::redefineMethod(
            get_parent_class(GrandChildStaticTestClass::class),
            'getString',
            '',
            'return "D";'
        );
        static::assertSame('CD', GrandChildStaticTestClass::getString());
    }

    /**
     * @TODO remove in 2.0.0 version
     */
    public function testInheritStaticMockWithOldNameSpace()
    {
        \QA\SoftMocks::redefineMethod(
            get_parent_class(GrandChildStaticTestClass::class),
            'getString',
            '',
            'return "D";'
        );
        static::assertSame('CD', GrandChildStaticTestClass::getString());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage You will never see this message
     */
    public function testNotOk()
    {
        throw new \RuntimeException("You will never see this message");

        $Mock = $this->getMock(MyTest::class, ['stubFunction']);
        $Mock->expects($this->any())->method('stubFunction')->willReturn(
            function () {
                yield 10;
            }
        );
    }

    public function stubFunction() {}

    public function testAnonymous()
    {
        static::markTestSkippedForPHPVersionBelow('7.0.0');

        require_once __DIR__ . '/AnonymousTestClass.php';
        static::assertEquals(1, AnonymousTestClass::SOMETHING);
        $test = new AnonymousTestClass();
        $obj = $test->doSomething();
        \Badoo\SoftMocks::redefineMethod(
            get_class($obj),
            'meth',
            '',
            'return "Test";'
        );
        static::assertSame('Test', $obj->meth());
    }

    public function testWithReturnTypeDeclarationsPHP7()
    {
        static::markTestSkippedForPHPVersionBelow('7.0.0');

        require_once __DIR__ . '/WithReturnTypeDeclarationsPHP7TestClass.php';

        \Badoo\SoftMocks::redefineMethod(
            WithReturnTypeDeclarationsPHP7TestClass::class,
            'getString',
            '',
            'return "string2";'
        );
        $res = WithReturnTypeDeclarationsPHP7TestClass::getString();
        static::assertSame("string2", $res);
    }

    public function testWithReturnTypeDeclarationsPHP71()
    {
        static::markTestSkippedForPHPVersionBelow('7.1.0');

        require_once __DIR__ . '/WithReturnTypeDeclarationsPHP71TestClass.php';

        \Badoo\SoftMocks::redefineMethod(
            WithReturnTypeDeclarationsPHP71TestClass::class,
            'getStringOrNull',
            '',
            'return "string3";'
        );
        $int = null;
        $res = WithReturnTypeDeclarationsPHP71TestClass::getStringOrNull($int);
        static::assertSame("string3", $res);
    }

    public function testWithPrivateConstantPHP71()
    {
        static::markTestSkippedForPHPVersionBelow('7.1.0');

        require_once __DIR__ . '/WithRestrictedConstantsPHP71TestClass.php';

        static::assertEquals(1, WithRestrictedConstantsPHP71TestClass::getPrivateValue());

        \Badoo\SoftMocks::redefineConstant('\Badoo\SoftMock\Tests\WithRestrictedConstantsPHP71TestClass::PRIVATE_VALUE', 2);

        static::assertEquals(2, WithRestrictedConstantsPHP71TestClass::getPrivateValue());
    }

    public function providerWithWrongPrivateConstantAccessPHP71()
    {
        return [
            'without mock' => [false],
            'with mock'    => [true],
        ];
    }

    /**
     * @dataProvider providerWithWrongPrivateConstantAccessPHP71
     *
     * @expectedException        \Error
     * @expectedExceptionMessage Cannot access private const
     *
     * @param bool $set_mock
     */
    public function testWithWrongPrivateConstantAccessPHP71($set_mock)
    {
        static::markTestSkippedForPHPVersionBelow('7.1.0');

        require_once __DIR__ . '/WithRestrictedConstantsPHP71TestClass.php';

        if ($set_mock) {
            \Badoo\SoftMocks::redefineConstant('\Badoo\SoftMock\Tests\WithRestrictedConstantsPHP71TestClass::PRIVATE_VALUE', 2);
        }

        WithWrongPrivateConstantAccessPHP71TestClass::getPrivateValue();
    }

    public function testWithProtectedConstantPHP71()
    {
        static::markTestSkippedForPHPVersionBelow('7.1.0');

        require_once __DIR__ . '/WithRestrictedConstantsPHP71TestClass.php';

        static::assertEquals(11, WithRestrictedConstantsPHP71TestClass::getProtectedValue());

        \Badoo\SoftMocks::redefineConstant('\Badoo\SoftMock\Tests\WithRestrictedConstantsPHP71TestClass::PROTECTED_VALUE', 22);

        static::assertEquals(22, WithRestrictedConstantsPHP71TestClass::getProtectedValue());
    }

    public function providerWithWrongProtectedConstantAccessPHP71()
    {
        return [
            'without mock' => [false],
            'with mock'    => [true],
        ];
    }

    /**
     * @dataProvider providerWithWrongProtectedConstantAccessPHP71
     *
     * @expectedException        \Error
     * @expectedExceptionMessage Cannot access protected const
     *
     * @param bool $set_mock
     */
    public function testWithWrongProtectedConstantAccessPHP71($set_mock)
    {
        static::markTestSkippedForPHPVersionBelow('7.1.0');

        require_once __DIR__ . '/WithRestrictedConstantsPHP71TestClass.php';

        if ($set_mock) {
            \Badoo\SoftMocks::redefineConstant('\Badoo\SoftMock\Tests\WithRestrictedConstantsPHP71TestClass::PROTECTED_VALUE', 22);
        }

        WithWrongProtectedConstantAccessPHP71TestClass::getProtectedValue();
    }

    public function providerRewrite()
    {
        $files = glob(__DIR__ . '/fixtures/original/*.php');
        $result = array_map(
            function ($filename) {
                return [basename($filename)];
            },
            $files
        );
        return array_combine(array_column($result, 0), $result);
    }

    /**
     * @dataProvider providerRewrite
     *
     * @param $filename
     */
    public function testRewrite($filename)
    {
        if (($filename === 'php7.php')) {
            static::markTestSkippedForPHPVersionBelow('7.0.0');
        }

        if (($filename === 'php71.php')) {
            static::markTestSkippedForPHPVersionBelow('7.1.0');
        }

        $result = \Badoo\SoftMocks::rewrite(__DIR__ . '/fixtures/original/' . $filename);
        $this->assertNotFalse($result, "Rewrite failed");

        //file_put_contents(__DIR__ . '/fixtures/expected/' . $filename, file_get_contents($result));
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/fixtures/expected/' . $filename)), file_get_contents($result));
    }
}
