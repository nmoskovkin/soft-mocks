<?php
$composerInstall = '';
foreach ([__DIR__ . '/../../../autoload.php', __DIR__ . '/../vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        $composerInstall = $file;

        break;
    }
}
$php_parser_dir = dirname($composerInstall) . "/nikic/php-parser/lib/PhpParser/";
require_once $php_parser_dir . "Autoloader.php";
\PhpParser\Autoloader::register(true);
// for prevent autoload problems
$files = [];
exec('find ' . escapeshellarg($php_parser_dir) . " -type f -name '*.php'", $files);
foreach ($files as $file) {
    require_once $file;
}
unset($files, $file);

/* Soft Mocks init */
require_once(dirname(__DIR__) . "/src/Badoo/SoftMocks.php");
// @TODO Should be removed after release 2.0
require_once(dirname(__DIR__) . "/src/QA/SoftMocks.php");
\Badoo\SoftMocks::init();
require \Badoo\SoftMocks::rewrite($composerInstall);
unset($php_parser_dir, $composerInstall);
