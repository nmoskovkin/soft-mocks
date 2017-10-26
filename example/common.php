<?php
define('TEST_CONSTANT_WITH_VALUE_42', 42);

function someFunc($a) {
    return abs($a * 42);
}

class Example {
    const STATIC_DO_SMTH_RESULT = 42;
    const DYNAMIC_DO_SMTH_RESULT = 84;

    private static $dataMap = [
        'test' => [
            'name' => 'Birthday',
            'group' => '123',
            'value_type' => '456',
            'source_type' => '789',
            'operators' => [
                '+',
            ],
            'key' => 'birthdate',
            'filter_sets' => [
//                some
//                commented
//                lines
            ],
        ],
    ];

    public static function doSmthStatic()
    {
        return self::STATIC_DO_SMTH_RESULT;
    }

    public function doSmthDynamic()
    {
        return self::DYNAMIC_DO_SMTH_RESULT;
    }
}

