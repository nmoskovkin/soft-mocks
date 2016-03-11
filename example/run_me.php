<?php
require_once('src/bootstrap.php');
require_once \QA\SoftMocks::rewrite('common.php');
require_once \QA\SoftMocks::rewrite('vendor/autoload.php'); // should be included rewritten if you want redefine external libs

require_once \QA\SoftMocks::rewrite('SoftMocksExample.php');

echo "Result before applying SoftMocks = " . var_export(SoftMocksExample::run(), 1) . PHP_EOL;
SoftMocksExample::applyMocks();
echo "Result after applying SoftMocks = " . var_export(SoftMocksExample::run(), 1) . PHP_EOL;
SoftMocksExample::revertMocks();
echo "Result after reverting SoftMocks = " . var_export(SoftMocksExample::run(), 1) . PHP_EOL;
