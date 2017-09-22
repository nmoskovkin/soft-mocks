<?php 






\Badoo\SoftMocks::callFunction(__NAMESPACE__, 'error_reporting', array(\Badoo\SoftMocks::getConst(__NAMESPACE__, 'E_ALL')));
\Badoo\SoftMocks::callFunction(__NAMESPACE__, 'ini_set', array('display_errors', true));

// Conditions
if (!empty($_SERVER['HTTP_ORIG_DOMAIN'])) {
    $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_ORIG_DOMAIN'];
    
    // Various strings
    $header = 'HTTP/1.1 301 Moved Permanently';
    $redirect_address = 'https://badoo.com';
    
    \Badoo\SoftMocks::callFunction(__NAMESPACE__, 'header', array($header));
    \Badoo\SoftMocks::callFunction(__NAMESPACE__, 'header', array('Location: ' . $redirect_address));
    
    echo <<<END
<html>\n <head>\n  <title>Status 301 - Moved Permanently</title>\n  <meta http-equiv="Refresh" content="0; url={$redirect_address}">\n </head>\n <body bgcolor="#ffffff" text="#000000" link="#ff0000" alink="#ff0000" vlink="#ff0000">\n Document permanently moved: <a href="{$redirect_address}">{$redirect_address}</a>\n </body>\n</html>
END;
    
    
    
    
    
    
    
    
    \Badoo\SoftMocks::callExit();}


$developer = 'somebody';

$_SERVER['developer'] = $developer;

@\Badoo\SoftMocks::callFunction(__NAMESPACE__, 'ini_set', array('error_log', '/local/logs/php/badoo-' . $developer . '.log'));

\Badoo\SoftMocks::callFunction(__NAMESPACE__, 'define', array('PHPWEB_PATH_PHOTOS', '/home/' . $developer . '/photos'));

$old_umask = \Badoo\SoftMocks::callFunction(__NAMESPACE__, 'umask', array(0));
$create_dirs = [
\Badoo\SoftMocks::getConst(__NAMESPACE__, 'PHPWEB_PATH_PHOTOS')];


include_once \Badoo\SoftMocks::rewrite('debug.php');

\Badoo\SoftMocks::callFunction(__NAMESPACE__, 'isRobotDebug', array());
\Badoo\SoftMocks::callFunction(__NAMESPACE__, 'isCssDebug', array());
if (\Badoo\SoftMocks::callFunction(__NAMESPACE__, 'isCompressHTMLDebug', array())) {\Badoo\SoftMocks::callFunction(__NAMESPACE__, 'define', array('COMPRESS_HTML_DEBUG', true));}

function replaceSomething($string){
    
    // Comment
    /* Comment */
    return \Badoo\SoftMocks::callFunction(__NAMESPACE__, 'str_replace', array('something', 'somebody', $string));}


class SomeClass{
    
    const VALUE = 1;
    
    public $a = 1;
    
    public static function getValue(){if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array();return eval($__softmocksvariableforcode);}/** @codeCoverageIgnore */
        
        return \Badoo\SoftMocks::getClassConst(self::class, 'VALUE');}
    
    
    public function method($string){if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);}/** @codeCoverageIgnore */
        
        return self::methodSelf($string);}
    
    
    protected static function methodSelf($string){if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);}/** @codeCoverageIgnore */
        
        return \Badoo\SoftMocks::callFunction(__NAMESPACE__, 'replaceSomething', array(&$string));}}