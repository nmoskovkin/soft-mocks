<?php
namespace Badoo;

$composer_install = require __DIR__ . '/init_with_composer.php';
/** @noinspection PhpIncludeInspection */
require $composer_install;
unset($composer_install);
