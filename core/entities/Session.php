<?php

class Session
{
    private static $id = '';
    
    
    public static function getId()
    {
        return self::$id;
    }

    public static function setId($id)
    {
        if( self::$id == '' ){
            self::$id = $id;
            self::createSessionPaths();
            self::createSessionResultsFile();
        } else {
            throw new ErrorException('Session already set');
        }
    }
    
    protected static function createSessionResultsFile()
    {
        $resultsFile = GENERATED_RESULTS_PATH . self::$id . DIRECTORY_SEPARATOR . 'results.txt';
        if (!file_exists($resultsFile)) {
            file_put_contents($resultsFile, PHP_EOL . "PHPUnit by Sebastian Bergmann. \nSeleniumShell by Wouter Wessendorp \n\n");
        }
    }

    protected static function createSessionPaths()
    {
        $paths = array(
            'SESSION_TESTSUITES_PATH' => GENERATED_TESTSUITES_PATH . Session::getId(),
            'SESSION_SETUP_BEFORE_PROJECT_PATH' => GENERATED_SETUP_BEFORE_PROJECT_PATH . Session::getId(),
            'SESSION_RESULTS_PATH' => GENERATED_RESULTS_PATH . Session::getId(),
            'SESSION_SCREENSHOTS_PATH' => GENERATED_SCREENSHOTS_PATH . Session::getId(),
            'SESSION_DEBUG_PATH' => GENERATED_DEBUG_PATH . Session::getId(),
            'SESSION_TESTSUITES_PATH' => GENERATED_TESTSUITES_PATH . Session::getId(),
        );
        new PathCreator( $paths );
    }
    

}
