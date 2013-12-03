<?php
$rel_path = getcwd();
// Constants
$sep = DIRECTORY_SEPARATOR;

define('SELENIUM_SHELL', str_replace('core', '', $rel_path));
define('CORE_CONFIG_PATH', $rel_path. $sep.'config' );
define('CORE_PATH', $rel_path . $sep);
define('CORE_SRC_PATH', $rel_path . $sep . 'src');
define('CORE_HANDLERS_PATH', $rel_path . $sep .'src'.$sep.'handlers');
define('UTILS_PATH', $rel_path . $sep . 'utils');

define('PROJECTS_FOLDER', SELENIUM_SHELL . 'projects');


function __autoload( $className ){
    echo $className . ' not found.. ';
}



require_once( CORE_HANDLERS_PATH . '/ProjectActionsInitiator.php' );
require_once( CORE_HANDLERS_PATH . '/ProjectHandlersInitiator.php' );
require_once( CORE_HANDLERS_PATH . '/ConfigHandler.php' );

require_once( CORE_PATH . '/utils/Request.php' );
require_once( CORE_PATH . '/utils/DirectoryScanner.php' );
require_once( CORE_PATH . '/utils/Response.php' );


require_once( CORE_SRC_PATH . '/SeleniumShell_Test.php' );
require_once( CORE_SRC_PATH . '/Application.php' );

