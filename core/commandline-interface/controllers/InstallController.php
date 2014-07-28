<?php



class InstallController extends Controller 
{
    
    protected $generatedFolders = array(
        GENERATED_PATH,
        GENERATED_RESULTS_PATH,
        GENERATED_SETUP_BEFORE_PROJECT_PATH,
        GENERATED_SCREENSHOTS_PATH,
        GENERATED_TESTSUITES_PATH,
        GENERATED_DEBUG_PATH,
        DOWNLOADS_PATH
    );
    
    public function getMandatoryArguments()
    {
        return array();
    }

    public function run()
    {
        $this->createGeneratedFolders();
        $this->downloadUpdates();
        $this->isPathVariableSet();
    }
    
    public function isPathVariableSet(){
        exec('echo ;%PATH%; | find /C /I ";' .BIN_PATH. ';"', $lines, $exitcode);
        return $exitcode === 0;
    }
    
    public function setPathVariable()
    {
        if( $this->isPathVariableSet() ){
            exec( 'set PATH=%PATH%;' . BIN_PATH );
        }
    }
    
    public function createGeneratedFolders()
    {
        $pathCreator = new PathCreator();
        $pathCreator->setPaths($this->generatedFolders);
        $pathCreator->createPaths();
    }
    
    public function downloadUpdates()
    {
        $updateController = new UpdateController();
        $updateController->run();
        
    }

    public static function getHelpDescription()
    {
        return 'TBI: For installing selenium shell';
    }

}