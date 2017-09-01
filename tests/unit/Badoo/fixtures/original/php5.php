<?php
/**
 * This file contains php5 code
 * And comments
 */

// Functions calls
error_reporting(E_ALL);
ini_set('display_errors', true);

// Conditions
if (!empty($_SERVER['HTTP_ORIG_DOMAIN'])) {
    $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_ORIG_DOMAIN'];

    // Various strings
    $header = 'HTTP/1.1 301 Moved Permanently';
    $redirect_address = 'https://badoo.com';

    header($header);
    header('Location: ' . $redirect_address);

    echo <<<END
<html>
 <head>
  <title>Status 301 - Moved Permanently</title>
  <meta http-equiv="Refresh" content="0; url=$redirect_address">
 </head>
 <body bgcolor="#ffffff" text="#000000" link="#ff0000" alink="#ff0000" vlink="#ff0000">
 Document permanently moved: <a href="$redirect_address">$redirect_address</a>
 </body>
</html>
END;
    exit;
}

$developer = 'somebody';

$_SERVER['developer'] = $developer;

@ini_set('error_log', '/local/logs/php/badoo-' . $developer . '.log');

define('PHPWEB_PATH_PHOTOS', '/home/' . $developer . '/photos');

$old_umask = umask(0);
$create_dirs = [
    PHPWEB_PATH_PHOTOS
];

include_once 'debug.php';

isRobotDebug();
isCssDebug();
if (isCompressHTMLDebug()) define('COMPRESS_HTML_DEBUG', true);

function replaceSomething($string)
{
    // Comment
    /* Comment */
    return str_replace('something', 'somebody', $string);
}

class SomeClass
{
    public $a = 1;

    public function method($string)
    {
        return self::methodSelf($string);
    }

    protected static function methodSelf($string)
    {
        return replaceSomething($string);
    }
}
