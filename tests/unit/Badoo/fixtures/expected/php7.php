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
    
    public function methodReturn() : string{if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array();return eval($__softmocksvariableforcode);/** @codeCoverageIgnore */}
        
        return self::methodSelf("string");}
    
    
    protected static function methodSelf($string) : string{if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);/** @codeCoverageIgnore */}
        
        return \Badoo\SoftMocks::callFunction(__NAMESPACE__, 'replaceSomething', array(&$string));}
    
    
    public function methodParam(string $string){if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);/** @codeCoverageIgnore */}
        
        return $string;}
    
    
    public function methodNullableParam(?string $string){if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);/** @codeCoverageIgnore */}
        
        return $string;}
    
    
    public function methodNullableReturn() : ?array{if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array();return eval($__softmocksvariableforcode);/** @codeCoverageIgnore */}
        
        return null;}
    
    
    public function methodVoidReturn() : void{if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array();eval($__softmocksvariableforcode);return;/** @codeCoverageIgnore */}
        
        echo "something";}
    
    
    public function methodNullableParamReturn(?string $string) : string{if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);/** @codeCoverageIgnore */}
        
        return $string ?? "string";}
    
    
    public function methodParamNullableReturn(string $string) : ?string{if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);/** @codeCoverageIgnore */}
        
        return $string ? $string : null;}
    
    
    public function methodNullableParamNullableReturn(?string $string) : ?string{if (false !== ($__softmocksvariableforcode = \Badoo\SoftMocks::isMocked(SomeClass::class, static::class, __FUNCTION__))) {$mm_func_args = func_get_args();$params = array($string);return eval($__softmocksvariableforcode);/** @codeCoverageIgnore */}
        
        return $string;}}