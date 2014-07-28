<?php

class Example_TestSuite extends Example_TestSetup
{
    
    public function testSearchingGoogle()
    {
        // navigate to the main page
        $this->actions->navigateTo(Example_Pages::MAIN);
        
        // create a form handler and pass along the session
        $formHandler = new SeleniumShell_FormHandler($this);
        // fill out the form by id of the element to the to be set field value
        $formHandler->mapValuesToElementsById(
            array('gbqfq', 'SeleniumShell')
        );
        // submit the form
        $formHandler->submitForm();
        
        // test if the title contains our search value
        $this->assertContains('SeleniumShell', $this->title());
    }
    
}