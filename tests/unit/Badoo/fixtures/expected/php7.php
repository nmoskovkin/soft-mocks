<?php 

/**
 * This file contains php7 code
 */
function replaceSomething($string) : string{
    
    // Comment
    /* Comment */
    return \Badoo\SoftMocks::callFunction(__NAMESPACE__, 'str_replace', array('something', 'somebody', $string));}


class SomeClass{
    
    public $a = 1;
    
    public function method($string) : string{if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);}/** @codeCoverageIgnore */
        
        return self::methodSelf($string);}
    
    
    protected static function methodSelf($string) : string{if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);}/** @codeCoverageIgnore */
        
        return \Badoo\SoftMocks::callFunction(__NAMESPACE__, 'replaceSomething', array(&$string));}}