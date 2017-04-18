<?php
$composerInstall = '';
foreach ([__DIR__ . '/../../../autoload.php', __DIR__ . '/../vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        $composerInstall = $file;

        break;
    }
}
$php_parser_dir = dirname($composerInstall) . "/nikic/php-parser/lib/PhpParser/";
require($php_parser_dir . "Autoloader.php");
\PhpParser\Autoloader::register(true);
/* Soft Mocks init */
require_once(dirname(__DIR__) . "/src/QA/SoftMocks.php");
\QA\SoftMocks::init();
require \QA\SoftMocks::rewrite($composerInstall);
unset($php_parser_dir, $composerInstall);
