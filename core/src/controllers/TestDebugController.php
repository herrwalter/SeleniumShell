<?php

/**
 * This controller should take care of debugging a test in the browser.
 * It will run a test live step by step
 */
class TestDebugController extends Controller
{
    protected $projectTests;
    protected static $step = 0;

    public static function getHelpDescription() {
        return 'Debug your test live, step by step';
    }
    
    public function getOptionalArguments() {
        return array(
            '-browser'
            );
    }
    
    public function getMandatoryArguments() {
        return array(
            '-project',
            '-test'
        );
    }
   
    public function run() {
        //run seleniumtest
        $p = new Process('phpunit --filter "' . ArgvHandler::getArgumentValue('-test'). '" SeleniumShell.php --ss-session "doemaarwat" --ss-project kwt');
        sleep(4);
        var_dump($p->getExitcode());
        var_dump($p->getCommand());
        
        $this->askControls();
    }
    
    public function askControls()
    {
        while ($c = fread(STDIN, 1)) {
            if ($c == ">"){
                self::$step++;
                echo self::$step;
            }
        }
    }
}