<?php
require_once(__DIR__ . '/../src/bootstrap.php');
require_once \Badoo\SoftMocks::rewrite(__DIR__ . '/common.php');
// should be included rewritten if you want redefine external libs
require_once \Badoo\SoftMocks::rewrite(__DIR__ . '/../vendor/autoload.php');

require_once \Badoo\SoftMocks::rewrite(__DIR__ . '/SoftMocksExample.php');

echo "Result before applying SoftMocks = " . var_export(SoftMocksExample::run(), 1) . PHP_EOL;
SoftMocksExample::applyMocks();
echo "Result after applying SoftMocks = " . var_export(SoftMocksExample::run(), 1) . PHP_EOL;
SoftMocksExample::revertMocks();
echo "Result after reverting SoftMocks = " . var_export(SoftMocksExample::run(), 1) . PHP_EOL;
