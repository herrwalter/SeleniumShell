<?php


class ArgvHandler {
    
    public static function getArgumentValue( $option ){
        global $argv;
        $key = array_search($option,$argv);
        // '-' argument is handled as key-pair value
        // '--' argument is handled as setter (thus true) 
        if( $key && $option[1] !== '-' ){
            return $argv[$key + 1];
        }
        return $key !== false;
    }
    
    public static function getArgumentValuesByArray( array $options )
    {
        $foundValues = array();
        foreach($options as $option){
            $isset = self::getArgumentValue($option);
            if( $isset ){
                $foundValues[$option] = $isset;
            }
        }
        return $foundValues;
    }
    
    public static function getUnsupportedArguments( $supportedArguments )
    {
        global $argv;
        $unsupportedArguments = array();
        foreach( $argv as $value ){ //loop over all giver arguments. And check if any '-' or '--' is unsupported
            if( $value[0] == '-' && array_search($value, $supportedArguments) === false ){
                $unsupportedArguments[] = $value;
            }
        }
        return $unsupportedArguments;
    }
    
    public static function getKeyIndex( $index ){
        global $argv;
        if(array_key_exists($index, $argv) ){
            return $argv[$key];
        }
        return false;
    }
    
}