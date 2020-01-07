<?php
namespace Badoo;

$composer_install = '';
foreach ([__DIR__ . '/../../../autoload.php', __DIR__ . '/../vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        $composer_install = $file;

        break;
    }
}
if (!$composer_install) {
    fwrite(
        STDERR,
        'You need to set up the project dependencies using Composer:' . PHP_EOL . PHP_EOL
            . '    composer install' . PHP_EOL . PHP_EOL
            . 'You can learn all about Composer on https://getcomposer.org/.' . PHP_EOL
    );

    die(1);
}

$php_parser_dir = dirname($composer_install) . '/nikic/php-parser/lib/PhpParser/';
// workaround for right load files, because now PhpParser uses composer autoload, which should be init later
require_once "{$php_parser_dir}Parser.php";
require_once "{$php_parser_dir}ParserAbstract.php";
require_once "{$php_parser_dir}PrettyPrinterAbstract.php";
require_once "{$php_parser_dir}Builder.php";
require_once "{$php_parser_dir}Builder/Declaration.php";
require_once "{$php_parser_dir}NodeVisitor.php";
require_once "{$php_parser_dir}NodeVisitorAbstract.php";
require_once "{$php_parser_dir}NodeTraverserInterface.php";
require_once "{$php_parser_dir}Node.php";
require_once "{$php_parser_dir}NodeAbstract.php";
require_once "{$php_parser_dir}Lexer/TokenEmulator/TokenEmulatorInterface.php";
require_once "{$php_parser_dir}Node/Expr.php";
require_once "{$php_parser_dir}Node/FunctionLike.php";
// for prevent autoload problems
$files = [];
exec('find ' . escapeshellarg($php_parser_dir) . " -type f -name '*.php'", $files);
sort($files);
foreach ($files as $file) {
    require_once $file;
}
unset($php_parser_dir, $files, $file);

/* Soft Mocks init */
require_once(dirname(__DIR__) . "/src/Badoo/SoftMocks.php");


function applyConfig($config) {
    if (array_key_exists('mock_cache_path', $config)) {
        SoftMocks::setMocksCachePath($config['mock_cache_path']);
        SoftMocks::setLockFilePath($config['mock_cache_path'] . '/soft_mocks_rewrite.lock');
    }
    if (array_key_exists('rewrite_internal', $config)) {
        SoftMocks::setRewriteInternal($config['rewrite_internal']);
    }
    if (array_key_exists('ignore_sub_paths', $config)) {
        $ignoreSubPaths = [];
        foreach ($config['ignore_sub_paths'] as $path) {
            $ignoreSubPaths[$path] = $path;
        }
        SoftMocks::setIgnoreSubPaths($ignoreSubPaths);
    }
    if (array_key_exists('callback_fix', $config)) {
        SoftMocks::setCallbackFixData($config['callback_fix']);
    }
    if (array_key_exists('functions', $config)) {
        $firstKey = array_keys($config['functions'])[0];
        if (!in_array($firstKey, ['allow', 'deny'])) {
            throw new \InvalidArgumentException('First key must be allow or deny');
        }
        SoftMocks::setIgnoreMode($firstKey === 'deny');
        SoftMocks::replaceIgnoreFunctions($config['functions'][$firstKey]);

        $required = [
            'call_user_func_array',
            'call_user_func',
            'is_callable',
            'function_exists',
            'constant',
            'defined',
            'debug_backtrace',
        ];
        if ($firstKey === 'allowed' && count(array_diff($required, $config['functions'][$firstKey])) > 0) {
            throw new \InvalidArgumentException('All required function must be allowed');
        }
        if ($firstKey === 'deny' && count(array_diff($required, $config['functions'][$firstKey])) != 0) {
            throw new \InvalidArgumentException('No required function must be deny');
        }

        if (isset($config['functions']['rules'])) {
            $functionSubpaths = [];
            foreach ($config['functions']['rules'] as $functionName => $rule) {
                if (isset($rule['subpaths'])) {
                    $functionSubpaths[$functionName] = $rule['subpaths'];
                }
            }
            SoftMocks::setFunctionSubpaths($functionSubpaths);
        }
    }
}

if ($configFile = \getenv('SOFTMOCKS_CONFIG')) {
    $config = require $configFile;
    \Badoo\applyConfig($config);
} else {
    SoftMocks::setIgnoreSubPaths(
        array(
            '/vendor/phpunit/' => '/vendor/phpunit/',
            '/vendor/sebastian/diff/' => '/vendor/sebastian/diff/',
            '/vendor/nikic/php-parser/' => '/vendor/nikic/php-parser/',
            '/vendor/symfony/polyfill' => '/vendor/symfony/polyfill',
            '/vendor/guzzlehttp/' => '/vendor/guzzlehttp/',
        )
    );
}

SoftMocks::setVendorPath(dirname($composer_install));
SoftMocks::init();
return SoftMocks::rewrite($composer_install);
