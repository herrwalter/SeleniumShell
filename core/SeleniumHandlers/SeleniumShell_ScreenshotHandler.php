<?php
/**
 * @class SeleniumShell_ScreenshotHandler
 * 
 * Responsible of where to put the screenshot and making
 * the screenshot.
 * 
 */
class SeleniumShell_ScreenshotHandler{
    
    private $session;
    private $path;
    private $prefix;
    
    public function __construct( $session, $path, $prefix='' ) {
        $this->setPath($path);
        $this->setSession($session);
        $this->setPrefix($prefix);
    }
    /**
     * Sets the paths
     * validates on
     *  - path existence
     *  - writability
     * 
     * @param string $path
     * @throws Exception
     */
    public function setPath( $path ){
        if( file_exists($path) && is_writable($path)){
            //path must end on a slash.
            if( substr($path, -1,1) !== '/' ){
                $path .= '/';
            }
            $this->path = $path;
        }
        elseif( !file_exists($path) ){
            throw new Exception( 'The path you provide does not exsist' );
        }
        else{
            throw new Exception( 'The path you provide is not writable' );
        }
    }
    
    /**
     * Sets the Session to the Grabber Class.
     * Validates is param is of the right type.
     * 
     * @param PHPUnit_Extensions_Selenium2TestCase $session
     * @throws Exception
     */
    public function setSession($session){
        if( $session instanceof PHPUnit_Extensions_Selenium2TestCase){
            $this->session = $session;
        }
        else{
            throw new Exception('The session you provide to the ScreenshotCamera must be an instance of PHPUnit_Extensions_Selenium2TestCase');
        }
    }
    /**
     * Set the prefix for the screenshot names.
     * (Could be the browser for example)
     * @param type $prefix
     */
    public function setPrefix( $prefix ){
        $this->prefix = $prefix;
    }
    
    /**
     * Makes the screenshot
     * @param string $name File name
     */
    public function makeScreenshot( $name ){
        $filename = $this->path . $name . '.jpg';
        $fp = (file_exists($filename))? fopen($filename, "a+") : fopen($filename, "w+");
        try{
            fwrite($fp, $this->session->currentScreenshot());
        }
        catch( Execption $error ){
            echo 'Sadly, we could not take a screenshot on this test see the error message: ';
            var_dump( $error );
        }
        fclose($fp);
    }
    
}