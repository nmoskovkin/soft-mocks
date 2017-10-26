<?php 

try {
    $a = \Badoo\SoftMocks::callFunction(__NAMESPACE__, 'replaceSomething', array("something"));} catch (\Exception $e) {
    
    echo $e->getMessage();} finally {
    
    echo "finally";}