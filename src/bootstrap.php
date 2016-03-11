<?php

define('SOFTMOCKS_ROOT_PATH', dirname(__DIR__) . '/');

/* I am terribly sorry for this kind of code */
$php_parser_dir = dirname(__DIR__) . "/vendor/nikic/php-parser/lib/PhpParser/";
require($php_parser_dir . "Autoloader.php");
\PhpParser\Autoloader::register(true);
$out = [];
exec('find ' . escapeshellarg($php_parser_dir) . " -type f -name '*.php'", $out);
foreach ($out as $f) {
    require_once($f);
}

require_once(dirname(__DIR__) . "/src/QA/SoftMocks.php");

\QA\SoftMocks::init();
\QA\SoftMocks::ignoreFiles(get_included_files());
