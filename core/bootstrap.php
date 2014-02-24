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
define('GENERATED_PATH', $rel_path. $sep. 'generated');
define('GENERATED_TESTSUITS_PATH', GENERATED_PATH . $sep . 'testsuits' .$sep );
define('PROJECTS_FOLDER', SELENIUM_SHELL . 'projects');


require_once( FILESCANNERS_PATH . '/FileScanner.php');
require_once( FILESCANNERS_PATH . '/TestFileScanner.php');

require_once( CORE_SRC_PATH . '/SeleniumShell_Test.php' );



function SeleniumShellAutoloadFunction( $className ){
    $files = array();
    
    $backtrace = debug_backtrace();
    $file = $backtrace[1]['file'];
    $explodedFile = explode(DIRECTORY_SEPARATOR, $file);
    // check if the last function called contains the projects path
    // then we should crawlup that project file..
    if(strpos( $file, GENERATED_TESTSUITS_PATH ) !== false ){
        $projectName = $explodedFile[count($explodedFile) - 2];
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
                }
            }
        }
    } else { // find seleniumShell Core.
        // crawl source..
        $seleniumSource = new FileScanner( CORE_SRC_PATH );
        foreach( $seleniumSource->getFilesInOneDimensionalArray() as $projectFile ){
            $explodedProjectFile = explode( DIRECTORY_SEPARATOR, $projectFile);
            $projectFileName = $explodedProjectFile[count($explodedProjectFile) - 1];
            $projectFileName = str_replace('.php', '', $projectFileName);
            if( strtolower($projectFileName) === strtolower($className) ){
                require_once $projectFile;
                return;
            }
        }
        // crawl utils
        $seleniumUtils = new FileScanner( UTILS_PATH );
        foreach( $seleniumUtils->getFilesInOneDimensionalArray() as $projectFile ){
            $explodedProjectFile = explode( DIRECTORY_SEPARATOR, $projectFile);
            $projectFileName = $explodedProjectFile[count($explodedProjectFile) - 1];
            $projectFileName = str_replace('.php', '', $projectFileName);
            if( strtolower($projectFileName) === strtolower($className) ){
                require_once $projectFile;
                return;
            }
        }
        
    }
}

spl_autoload_register('SeleniumShellAutoloadFunction');




