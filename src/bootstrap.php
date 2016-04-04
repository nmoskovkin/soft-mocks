<?php
/* You must preload PHP Parser before initializing Soft Mocks so that it does not try to rewrite it */
$php_parser_dir = dirname(__DIR__) . "/vendor/PHP-Parser/lib/PhpParser/";
require($php_parser_dir . "Autoloader.php");
\PhpParser\Autoloader::register(true);
$out = [];
exec('find ' . escapeshellarg($php_parser_dir) . " -type f -name '*.php'", $out);
foreach ($out as $f) {
    require_once($f);
}

/* Soft Mocks init */
require_once(dirname(__DIR__) . "/src/QA/SoftMocks.php");
\QA\SoftMocks::init();
