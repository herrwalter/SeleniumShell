<?php

class Example_PageActions {

    /**
     * This is the webdriver reference of the test that is running
     * @var SeleniumShell_Test 
     */
    protected $session;
    
    /**
     * The url handler
     * @var Example_URLHandler 
     */
    protected $urlHandler;
    
    /**
     * As for the parameter: we will need the session of the current
     * test as we need to interact with the browser for most of the actions.
     * @param SeleniumShell_Test $session
     */
    public function __construct(SeleniumShell_Test $session) {
        $this->session = $session;
        $this->urlHandler = new Example_URLHandler();
    }

    /**
     * Will navigate to one the pages predefined in the URLHandler.
     * @param string $pageConstant
     */
    public function navigateTo($pageConstant) {
        $url = $this->urlHandler->getURL(Example_Pages::MAIN);
        $this->session->url($url);
    }

}
