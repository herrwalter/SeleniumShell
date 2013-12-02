<?php

// include SeleniumShell
$rel_path = substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))));
require( $rel_path . '/../../core/src/SeleniumShellProject.php' );

class projectXText extends SeleniumShellProject{
    
    public $config = array(
        'projectName' => 'Project X',
        'projectFolder' => 'projectX',
        'emailResults' => 'fake@email.com'
    );    
    
    
    public function testProjectX()
    {
        // leave blank to trigger phpunit 
    }
    
}