<?php

class DebugLog {
    
    public static function write( $text )
    {
        $file = GENERATED_DEBUG_PATH . Session::getId() . DIRECTORY_SEPARATOR . 'debug.txt';
        file_put_contents($file , print_r($text, true) . PHP_EOL, FILE_APPEND);
    }
    
}