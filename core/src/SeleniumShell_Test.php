<?php


class SeleniumShell_Test extends PHPUnit_Extensions_Selenium2TestCase
{
    public $projectName;
    
    public $browser = 'firefox';
    
    /** @var _config ConfigHandler */
    private $_config ;
    
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
    }
    
    public function setUp()
    {
        $this->_config = new ConfigHandler( CORE_CONFIG_PATH . '/config.ini');
        $this->setBrowser(strtolower($this->browser));
        $this->setBrowserUrl('/');
        $this->setHost($this->getSeleniumHost());
        $this->setPort($this->getSeleniumPort());
        $this->setSeleniumServerRequestsTimeout(5000);
        $this->setDesiredCapabilities(array());
    }
    
    public function getSeleniumHost(){
        if( $this->_config->isParameterSet('--ss-host') ){
            return $this->_config->getParameter( '--ss-host' );
        } else if( $this->_config->getAttribute('host') ) {
            return $this->_config->getAttribute('host');
        } else {
            return '127.0.0.1';
        }
    }
    
    public function getSeleniumPort(){
        if( $this->_config->isParameterSet('--ss-port') ){
            return (int) $this->_config->getParameter( '--ss-port' );
        } else if($this->_config->getAttribute('port')) {
            return (int) $this->_config->getAttribute('port');
        } else {
            return 4444;
        }
    }
}