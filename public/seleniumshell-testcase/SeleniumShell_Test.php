<?php


class SeleniumShell_Test extends SeleniumShell_ErrorCatchingOverrides
{
    public $projectName;
    
    public $ss_browser_info = array();
    
    /** @var _config ConfigHandler */
    private $_config ;
    
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
    }
    
    public function setUp()
    {
        $this->_config = new ConfigHandler( CORE_CONFIG_PATH . '/config.ini');
        $this->setBrowserUrl('/');
        $this->setDesiredCapabilities($this->ss_browser_info);
        $this->setHost($this->getSeleniumHost());
        $this->setPort($this->getSeleniumPort());
        $this->setSeleniumServerRequestsTimeout(5000);
    }
    
    public function getSeleniumHost(){
        if( $this->_config->isParameterSet('--ss-host') ){
            return $this->_config->getParameter( '--ss-host' );
        } else if( $this->_config->getAttribute('host') ) {
            return $this->_config->getAttribute('host');
        } else {
            return '127.0.0.1';
        }
        
        $result = new PHPUnit_Framework_TestResult();
        $test = new PHPUnit_Framework_Test();
       
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
    
    

    public function getStatusRepresentation(){
        switch($this->getStatus()){
            case 0:
                return '.';
            case 1:
                return 'S';
            case 2:
                return 'I';
            case 3:
                return 'F';
            case 4:
                return 'E';
            default:
                throw new ErrorException( 'Unknows status found' );
        }
    }
        
    public function onNotSuccessfulTest(\Exception $e) {
        
        $subject = 'SeleniumShell Error on test: ' . $this->getTestId();
        $headers   = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type: text/plain; charset=iso-8859-1";
        $headers[] = "From: SeleniumShell <herrwalter@gmail.com>";
        $headers[] = "Reply-To: No replay <noreplay@google.nl>";
        $headers[] = "Subject: {$subject}";
        $headers[] = "X-Mailer: PHP/".phpversion();
        
        $message  = 'Failure of the test: ' . $this->getTestId() . PHP_EOL;
        $message .= 'Errorcode: ' . $e->getCode() . PHP_EOL;
        $message .= 'ErrMessge: ' . $e->getMessage() . PHP_EOL;
        $message .= 'Strace: ' . $e->getTraceAsString() . PHP_EOL;
        $message .= PHP_EOL;
        try{
            //mail( 'arnoud@trimm.nl' , $subject, $message, implode("\r\n", $headers));
        } catch (Exception $ex) {
            throw new Exception('Could not send the darn mail: ' . $ex->getMessage());;
        }
        parent::onNotSuccessfulTest($e);
    }
    
}
