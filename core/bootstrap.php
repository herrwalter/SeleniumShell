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
define('FILEINCLUDERS_PATH', UTILS_PATH . '/FileIncluders');
define('FILESCANNERS_PATH', UTILS_PATH . '/FileScanners');
define('CLASSHELPERS_PATH', UTILS_PATH . '/ClassHelpers');
define('TOKENHELPERS_PATH', UTILS_PATH . '/TokenHelpers');

define('PROJECTS_FOLDER', SELENIUM_SHELL . 'projects');


function __autoload( $className ){
    echo $className . ' not found.. ';
}



require_once( CORE_HANDLERS_PATH . '/ProjectActionsInitiator.php' );
require_once( CORE_HANDLERS_PATH . '/ProjectHandlersInitiator.php' );
require_once( CORE_HANDLERS_PATH . '/ConfigHandler.php' );

require_once( UTILS_PATH . '/Request.php' );
require_once( UTILS_PATH . '/Response.php' );
require_once( UTILS_PATH . '/AnnotationReader.php' );

require_once( CLASSHELPERS_PATH . '/ClassInstantiator.php');
require_once( CLASSHELPERS_PATH . '/TestClassReader.php');

require_once( FILEINCLUDERS_PATH . '/FileIncluder.php');
require_once( FILEINCLUDERS_PATH . '/PHPFileIncluder.php');
require_once( FILEINCLUDERS_PATH . '/TestFileIncluder.php');

require_once( FILESCANNERS_PATH . '/FileScanner.php');
require_once( FILESCANNERS_PATH . '/TestFileScanner.php');

require_once( CORE_SRC_PATH . '/SeleniumShell_Test.php' );
require_once( CORE_SRC_PATH . '/TestSuiteInitiator.php');
require_once( CORE_SRC_PATH . '/Project.php' );
require_once( CORE_SRC_PATH . '/Application.php' );

require_once( TOKENHELPERS_PATH . '/TokenClosure.php' );
require_once( TOKENHELPERS_PATH . '/TokenReader.php' );

