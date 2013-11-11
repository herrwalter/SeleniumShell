<?php


include('projects/projectX/init/bootstrap.php');


class Test extends SeleniumShell{

    public function testTesting(){
        
        $this->handlers->ExampleHandler->echoHandlerName();
        $this->actions->ExampleAction->echoActionName();
        $this->assertTrue(true);
    }
    
}