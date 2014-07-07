<?php
$rel_path = getcwd();
// Constants
$sep = DIRECTORY_SEPARATOR;

error_reporting(E_ALL);

define('SELENIUM_SHELL', str_replace('core', '', $rel_path));
define('CORE_CONFIG_PATH', SELENIUM_SHELL . $sep.'config' );
define('CORE_PATH', $rel_path . $sep);
define('BIN_PATH', SELENIUM_SHELL . 'bin');
define('DOWNLOADS_PATH', SELENIUM_SHELL . 'downloads');
define('CONTROLLER_PATH', CORE_PATH . 'src' . $sep . 'controllers');
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
define('COLORCHECKER_PATH', CORE_SRC_PATH . $sep . 'colorchecker' . $sep . 'ColorChecker.js');
define('PHPUNIT_PATH', 'C:\wamp\bin\php\php5.3.13\pear\PHPUnit\Autoload.php');

require_once(PHPUNIT_PATH);
require_once( FILESCANNERS_PATH . $sep . 'FileScanner.php');
require_once( FILESCANNERS_PATH . $sep . 'ControllerFileScanner.php');
require_once( FILESCANNERS_PATH . $sep . 'TestFileScanner.php');
require_once( CORE_HANDLERS_PATH . $sep . 'ConfigHandler.php');
require_once( UTILS_PATH . $sep . 'DebugLog.php');
require_once( CORE_HANDLERS_PATH . $sep . 'PHPUnitParameterReader.php');
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_HelperMethods.php' );
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_Asserts.php' );
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_ErrorCatchingOverrides.php' );
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_Test.php' );
require_once( SELENIUM_SHELL_TESTCASE . $sep . 'SeleniumShell_ColorCheckerTest.php' );


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
        scan_folder_for_class(SELENIUM_SHELL_TOOLS, $className);
        
    } else { // find seleniumShell Core.
        // crawl source..
        
        
        if( scan_folder_for_class(CORE_SRC_PATH, $className) ){} 
        // crawl utils.. 
        else if( scan_folder_for_class(UTILS_PATH, $className) ){}
        
        else if( scan_folder_for_class(SELENIUM_SHELL_TOOLS, $className)){}
        
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

