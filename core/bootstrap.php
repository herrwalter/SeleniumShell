<?php
$rel_path = getcwd();
// Constants
$sep = DIRECTORY_SEPARATOR;

error_reporting(E_ALL);

define('SELENIUM_SHELL', str_replace('core', '', $rel_path));
define('CORE_CONFIG_PATH', $rel_path. $sep.'config' );
define('CORE_PATH', $rel_path . $sep);
define('SELENIUM_SHELL_HANDLERS', CORE_PATH . $sep . 'SeleniumHandlers');
define('CORE_SRC_PATH', $rel_path . $sep . 'src');
define('CORE_HANDLERS_PATH', $rel_path . $sep .'src'.$sep.'handlers');
define('UTILS_PATH', $rel_path . $sep . 'utils');
define('FILEINCLUDERS_PATH', UTILS_PATH . $sep . 'FileIncluders');
define('FILESCANNERS_PATH', UTILS_PATH . $sep . 'FileScanners');
define('CLASSHELPERS_PATH', UTILS_PATH . $sep . 'ClassHelpers');
define('TOKENHELPERS_PATH', UTILS_PATH . $sep .'TokenHelpers');
define('GENERATED_PATH', $rel_path. $sep. 'generated');
define('GENERATED_TESTSUITES_PATH', GENERATED_PATH . $sep . 'testsuites' .$sep );

require_once( FILESCANNERS_PATH . $sep . 'FileScanner.php');
require_once( FILESCANNERS_PATH . $sep . 'TestFileScanner.php');
require_once( CORE_HANDLERS_PATH . $sep . 'ConfigHandler.php');
require_once( CORE_HANDLERS_PATH . $sep . 'PHPUnitParameterReader.php');
require_once( CORE_SRC_PATH . $sep . 'SeleniumShell_HelperMethods.php' );
require_once( CORE_SRC_PATH . $sep . 'SeleniumShell_Asserts.php' );
require_once( CORE_SRC_PATH . $sep . 'SeleniumShell_Test.php' );


function SeleniumShellAutoloadFunction( $className ){
    $backtrace = debug_backtrace();
    if( !isset($backtrace[1]['file']) ){
        return;
    }
    $file = $backtrace[1]['file'];
    $file = str_replace(PROJECTS_FOLDER, '', $file);
    $file = str_replace(GENERATED_TESTSUITES_PATH, '', $file);
    if( isset( $file[0] ) && $file[0] == DIRECTORY_SEPARATOR ){
        $file = substr($file, 1);
    }
    if( !$file ){
        return;
    }
    $explodedFile = explode(DIRECTORY_SEPARATOR, $file);
    
    
    // check if the last function called contains the projects path
    // then we should crawlup that project file..
    if(stripos( $file, CORE_PATH ) === false ){
        $projectName = $explodedFile[0];
        $projectPath = PROJECTS_FOLDER . DIRECTORY_SEPARATOR . $projectName;
        $projectScanner = new FileScanner($projectPath);
        $projectTestFileScanner = new TestFileScanner($projectPath);
        $projectTestFiles = $projectTestFileScanner->getFilesInOneDimensionalArray();
        // find our lost classes in the projectfolder
        foreach( $projectScanner->getFilesInOneDimensionalArray() as $projectFile ){
            // we should not include the testfiles.
            if( !in_array($projectFile, $projectTestFiles)){
                $explodedProjectFile = explode( DIRECTORY_SEPARATOR, $projectFile);
                $projectFileName = $explodedProjectFile[count($explodedProjectFile) - 1];
                $projectFileName = str_replace('.php', '', $projectFileName);
                if( strtolower($projectFileName) === strtolower($className) ){
                    require_once $projectFile;
                    return;
                }
            }
        }
        // if it isnt in the project folder, we assume the want to use a SeleniumShell Handler..
        scan_folder_for_class(SELENIUM_SHELL_HANDLERS, $className);
        
    } else { // find seleniumShell Core.
        // crawl source..
        if( scan_folder_for_class(CORE_SRC_PATH, $className) ){} 
        // crawl utils.. 
        else if( scan_folder_for_class(UTILS_PATH, $className) ){}
        
        else if( scan_folder_for_class(SELENIUM_SHELL_HANDLERS, $className)){}
        
    }
}

function scan_folder_for_class( $path, $className ){
    $seleniumSource = new FileScanner( $path );
    foreach( $seleniumSource->getFilesInOneDimensionalArray() as $projectFile ){
        $explodedProjectFile = explode( DIRECTORY_SEPARATOR, $projectFile);
        $projectFileName = $explodedProjectFile[count($explodedProjectFile) - 1];
        $projectFileName = str_replace('.php', '', $projectFileName);
        if( strtolower($projectFileName) === strtolower($className) ){
            require_once $projectFile;
            return true;
        }
    }
    return false;
}

spl_autoload_register('SeleniumShellAutoloadFunction');


$config = new ConfigHandler(CORE_CONFIG_PATH . DIRECTORY_SEPARATOR . 'config.ini');
$projectPath = $config->getAttribute('projects-path');
/** Define the project path. */
if( $projectPath ){
    define('PROJECTS_FOLDER', $projectPath);
}else{
    define('PROJECTS_FOLDER', SELENIUM_SHELL . 'projects');
}

define( 'PHPUNIT_PATH', $config->getAttribute('phpunit-path'));

try{
    require_once PHPUNIT_PATH . DIRECTORY_SEPARATOR . 'Autoload.php';
} catch(Exception $e){
    throw new Exception('PHPUNIT path not set in core/config/config.ini. Set it as "phpunit-path". It should be the full path to the location of phpunit');
}

