<?php

try {
    $a = replaceSomething("something");
} catch (\Exception $e) {
    echo $e->getMessage();
} finally {
    echo "finally";
}
