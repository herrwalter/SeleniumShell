<?php
$rel_path = getcwd();
// Constants
$sep = DIRECTORY_SEPARATOR;

error_reporting(E_ALL);

define('SELENIUM_SHELL', str_replace('core', '', $rel_path));
define('CORE_CONFIG_PATH', SELENIUM_SHELL . $sep.'config' );
define('CORE_PATH', $rel_path . $sep);
define('BIN_PATH', SELENIUM_SHELL . 'bin');
define('LIBRARY_PATH', SELENIUM_SHELL . 'library');
define('DOWNLOADS_PATH', SELENIUM_SHELL . 'downloads');
define('CONTROLLER_PATH', CORE_PATH . 'commandline-interface' . $sep . 'controllers');
define('SELENIUM_SHELL_PUBLIC', SELENIUM_SHELL . 'public' );
define('SELENIUM_SHELL_TOOLS', SELENIUM_SHELL_PUBLIC. DIRECTORY_SEPARATOR . 'seleniumshell-tools');
define('SELENIUM_SHELL_TESTCASE', SELENIUM_SHELL_PUBLIC . DIRECTORY_SEPARATOR . 'seleniumshell-testcase');
define('CORE_SRC_PATH', $rel_path . $sep . 'src');
define('CORE_HANDLERS_PATH', $rel_path . $sep .'src'.$sep.'handlers');
define('UTILS_PATH', $rel_path . $sep . 'utils');
define('FILEINCLUDERS_PATH', UTILS_PATH . $sep . 'FileIncluders');
define('FILESCANNERS_PATH', UTILS_PATH . $sep . 'FileScanners');
define('CLASSHELPERS_PATH', UTILS_PATH . $sep . 'ClassHelpers');
define('TOKENHELPERS_PATH', UTILS_PATH . $sep .'TokenHelpers');
define('GENERATED_PATH', SELENIUM_SHELL . 'generated');
define('GENERATED_TESTSUITES_PATH', GENERATED_PATH . $sep . 'testsuites' .$sep );
define('GENERATED_RESULTS_PATH', GENERATED_PATH . $sep . 'results' .$sep );
define('GENERATED_DEBUG_PATH', GENERATED_PATH . $sep . 'debug' .$sep );
define('GENERATED_SCREENSHOTS_PATH', GENERATED_PATH . $sep . 'screenshots' .$sep );
define('GENERATED_SETUP_BEFORE_PROJECT_PATH', GENERATED_PATH . $sep . 'setup-before-project' .$sep );
define('COLORCHECKER_PATH', SELENIUM_SHELL . 'library' . $sep . 'colorchecker' . $sep . 'ColorChecker.js');

/**
 * Load phpunit for a windows system.
 */
$wherePHPunit = exec('where phpunit', $output );
$phpunitBase = str_replace('\phpunit', '',$output[0]);
if( trim($output[0]) == 'INFO: Could not find files for the given pattern(s).'){
    throw new ErrorException('could not find phpunit, please install it and add it to you windows env');
}
// lets set the current dir to the path of the first phpunit executable we've found
$curDir = getcwd();
chdir($phpunitBase);
// then search for the phpunit autoloader..
exec('dir /B/S Autoload.php', $autloadfiles);
foreach($autloadfiles as $file){
    $basepaths = pathinfo($file, PATHINFO_DIRNAME);
    $explodedBasepaths = explode('\\', $basepaths);
    // it will be the one thats in the PHPUnit folder.
    if(strtolower(array_pop($explodedBasepaths)) == 'phpunit' ){
        require_once $file;
        break;
    }
}
// set the dir back to where we where anyway
chdir($curDir);

require_once( FILESCANNERS_PATH . $sep . 'FileScanner.php');
require_once( FILESCANNERS_PATH . $sep . 'ControllerFileScanner.php');
require_once( FILESCANNERS_PATH . $sep . 'TestFileScanner.php');
require_once( CORE_HANDLERS_PATH . $sep . 'ConfigHandler.php');
require_once( UTILS_PATH . $sep . 'Log' . $sep . 'DebugLog.php');
require_once( CORE_HANDLERS_PATH . $sep . 'PHPUnitParameterReader.php');
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_HelperMethods.php' );
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_Asserts.php' );
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_ErrorCatchingOverrides.php' );
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_Test.php' );
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_ColorCheckerTest.php' );
require_once( LIBRARY_PATH . $sep . 'PHPMailer' . $sep . 'PHPMailerAutoload.php');


function SeleniumShellAutoloadFunction( $className ){
    $backtrace = debug_backtrace();
    if( !isset($backtrace[1]['file']) ){
        return;
    }
    
    $file = $backtrace[1]['file'];
    // try to strip serveral paths from file to determine if its a project class or a SeleniumShell class.
    $file = str_ireplace(PROJECTS_FOLDER, '', $file);
     
    $file = str_ireplace(GENERATED_TESTSUITES_PATH, '', $file);
    
    if( isset( $file[0] ) && $file[0] == DIRECTORY_SEPARATOR ){
        $file = substr($file, 1);
    }
    if( !$file ){
        return;
    }
    $explodedFile = explode(DIRECTORY_SEPARATOR, $file);
    
    // check if the last function called contains the projects path
    // then we should crawlup that project file..
    if(stripos( $file, CORE_PATH ) === false && stripos($file, BIN_PATH) === false ){
        $projectName = ArgvHandler::getArgumentValue('-project');
//        if( $projectName == ArgvHandler::getArgumentValue('-project') ){
//            $projectName = array_pop($explodedFile);
//        }
        if( substr($projectName, -1) == ':' ){
            scan_folder_for_class(SELENIUM_SHELL_TOOLS, $className);
            return;
        }
        
        $projectPath = PROJECTS_FOLDER  . $projectName;
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
        scan_folder_for_class(SELENIUM_SHELL_TOOLS, $className);
        
        // are we playing with the example-project?
        scan_folder_for_class(SELENIUM_SHELL . DIRECTORY_SEPARATOR . 'documentation' . DIRECTORY_SEPARATOR . 'example-project', $className);
        
    } else { // find seleniumShell Core.
        // crawl source..
        scan_folder_for_class(CORE_SRC_PATH, $className);
        scan_folder_for_class(UTILS_PATH, $className);
        scan_folder_for_class(CORE_PATH . 'entities', $className);
        scan_folder_for_class(CONTROLLER_PATH, $className);
        scan_folder_for_class(SELENIUM_SHELL_TOOLS, $className);
        scan_folder_for_class(LIBRARY_PATH, $className);
        
    }
}

function scan_folder_for_class( $path, $className ){
    $seleniumSource = new FileScanner( $path );
    foreach( $seleniumSource->getFilesInOneDimensionalArray() as $projectFile ){
        $projectFileName = pathinfo($projectFile, PATHINFO_FILENAME);
        //$explodedProjectFile = explode( DIRECTORY_SEPARATOR, $projectFile);
        //$projectFileName = $explodedProjectFile[count($explodedProjectFile) - 1];
        //$projectFileName = str_replace('.php', '', $projectFileName);
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


try{
    //require_once PHPUNIT_PATH . DIRECTORY_SEPARATOR . 'Autoload.php';
} catch(Exception $e){
    throw new Exception('PHPUNIT path not set in core/config/config.ini. Set it as "phpunit-path". It should be the full path to the location of phpunit');
}

