<?php
class SoftMocksExample
{
    public static function run()
    {
        $result = [
            'TEST_CONSTANT_WITH_VALUE_42' => TEST_CONSTANT_WITH_VALUE_42,
            'someFunc(2)' => someFunc(2),
            'Example::doSmthStatic()' => Example::doSmthStatic(),
            'Example->doSmthDynamic()' => (new Example)->doSmthDynamic(),
            'Example::STATIC_DO_SMTH_RESULT' => Example::STATIC_DO_SMTH_RESULT,
        ];

        return $result;
    }

    public static function applyMocks()
    {
        \Badoo\SoftMocks::redefineConstant('TEST_CONSTANT_WITH_VALUE_42', 43);
        \Badoo\SoftMocks::redefineConstant('\Example::STATIC_DO_SMTH_RESULT', 'Example::STATIC_DO_SMTH_RESULT value changed');

        \Badoo\SoftMocks::redefineFunction('someFunc', '$a', 'return 55 + $a;');
        \Badoo\SoftMocks::redefineMethod(Example::class, 'doSmthStatic', '', 'return "Example::doSmthStatic() redefined";');
        \Badoo\SoftMocks::redefineMethod(Example::class, 'doSmthDynamic', '', 'return "Example->doSmthDynamic() redefined";');
    }

    public static function revertMocks()
    {
        \Badoo\SoftMocks::restoreAll();
    }
}
