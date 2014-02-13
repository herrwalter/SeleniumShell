<?php


class SeleniumShell_Test extends PHPUnit_Extensions_Selenium2TestCase
{
    public $projectName;
    
    public $browser = 'firefox';
    
    public function setUp()
    {
        $this->setBrowser(strtolower($this->browser));
        $this->setBrowserUrl('http://www.google.nl');
        $this->setHost('127.0.0.1');
        $this->setPort(4444);
        $this->setSeleniumServerRequestsTimeout(5000);
        $this->setDesiredCapabilities(array());
    }
    
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
    }
}