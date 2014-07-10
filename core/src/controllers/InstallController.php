<?php



class InstallController extends Controller 
{
    
    public function getMandatoryArguments()
    {
        return array();
    }

    public function run()
    {
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