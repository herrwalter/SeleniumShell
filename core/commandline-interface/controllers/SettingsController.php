<?php

class SettingsController extends Controller 
{
    protected $currentBrowserNames;
    
    protected function setCurrentBrowserNames(){
        $config = new ConfigHandler(CORE_CONFIG_PATH . '/config.ini', true);
        $this->currentBrowserNames = $config->getAttribute('browsers', 'core-config');
    }


    public function askUniqueBrowserName()
    {
        $question = new OpenCLQuestion( 'Please give a unique name for this browsersetting ( ie. windowsIE8 )' );
        $question->askQuestion();
        return $question->getAwnser();
    }
    
    public function askBrowser()
    {
        $question = new OpenCLQuestion( 'What is the name of the browser? (ie. chrome, firefox, iexplore)' );
        $question->askQuestion();
        return $question->getAwnser();
    }
    
    public function askVersion()
    {
        $question = new OpenCLQuestion( 'What is the version of the browser? (ie. 8, 9, 10) type "-" if its not necessary ' );
        $question->askQuestion();
        return $question->getAwnser();
    }
       
    public function askPlatform()
    {
        $question = new OpenCLQuestion( 'What platform is running the browser? (ie. Windows, Linux) type "-" if its not necessary ' );
        $question->askQuestion();
        return $question->getAwnser();
    }
    
    public function listCurrentBrowsers()
    {
        $this->setCurrentBrowserNames();
        echo PHP_EOL;
        foreach($this->currentBrowserNames as $browserName ){
            $this->outputBrowserSection($browserName);
        }
    }
    
       
    public function outputBrowserSection( $browserName ){
        $config = new ConfigHandler(CORE_CONFIG_PATH . '/config.ini', true);
        $section = $config->getAttribute('browser-' . $browserName);
            echo $browserName . ': ' . PHP_EOL;
            foreach($section as $key => $value ){
                echo ' ' . $key . "\t" . $value . PHP_EOL;  
            }
            echo PHP_EOL;
    }
    
    public function addBrowser()
    {
        $uniquename = false;
        while( !$uniquename ){
            $name = $this->askUniqueBrowserName();
            $uniquename = !in_array($name, $this->currentBrowserNames);
        }
        $config = new ConfigHandler(CORE_CONFIG_PATH . '/config.ini', true);
        $config->addAttributeValue('browsers', 'core-config', $name);
        
        $browser = $this->askBrowser();
        $version = $this->askVersion();
        $platform = $this->askPlatform();
        
        $settings = array(
            'browserName' => $browser,
            'version' => $version,
            'platform' => $platform
        );
        
        $config->addSection('browser-'.$name, array_diff($settings, array('-')));
        
    }
    
    public function run() {
        foreach($this->getArguments() as $argument => $value ){
           switch( $argument ){
               case '--addBrowser':
                   $this->setCurrentBrowserNames();
                   $this->addBrowser();
                   break;
               case '--listBrowsers':
                   $this->listCurrentBrowsers();
                   break;
           }
        }
    }
    
    public function getOptionalArguments() {
        return array(
            '--addBrowser',
            '--listBrowsers'
        );
    }
    
    public function getMinNrOfRequiredArguments() {
        return 1;
    }
    
    public static function getHelpDescription() {
        return 'Can be used to set settings as: ' . PHP_EOL .
            "\t" .' --listBrowsers' . "\t" . "lists all knows browsers" . PHP_EOL .
            "\t" .' --addBrowsers' . "\t" . "add a new browser setting" . PHP_EOL;
    }
    
}