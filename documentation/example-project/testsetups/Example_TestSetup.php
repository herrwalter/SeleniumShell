<?php


class Example_TestSetup extends SeleniumShell_Test
{
    /**
     * @var Example_PageActions 
     */
    protected $actions;
    /**
     * Standard setup function that gets called before every test.
     * We will assign all variables here
     */
    public function setUp()
    {
        parent::setUp();
        $this->actions = new Example_PageActions( $this );
        
        $this->setBrowserUrl('/');
        // we will allways need to call the parent of the setup because there
        // are some setup dependancies running here.
    }
}