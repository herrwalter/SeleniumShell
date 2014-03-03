<?php



class eLeadschromeEleadsAdministratorTest extends SeleniumShell_Test {

	public $browser = "chrome";

    /* @var EleadsFunctions */
    protected $eLeads;
    /** $var VcodeHandler */
    protected $vCodes;
    
    
    public function __construct(){
        $dir = explode('\\', __file__); 
        $this->setBrowserUrl('/');
        $this->grabber = new Grabber( $this, 'C:/SeleniumTests/Screenshots', $this->getBrowser() );
        $this->eLeads = new EleadsActions( $this );
        $this->vCodes = new VcodeHandler();
    }
    
    protected function tearDown() {
        parent::tearDown();
        var_dump( $this->browser . ' ' . $this->getName() );
        
        $this->grabber->makeScreenshot( $this->browser . ' ' . $this->getName() );
        
    }

    /**
     * @ss-solo-run true
     * @ss-browsers chrome
     */
    public function testVcodeColumnVisibleForBPOAdmin(){
        $this->eLeads->openOverviewPageAsAdminBPO();
        $visible = true;
        try{
            $vcode = $this->byCssSelector('th.status.vcode');
        }
        catch( Exception $e ){
            $visible = false;
        }
        $this->assertTrue($visible, 'Vcode column is not visible');
        $this->assertEquals($vcode->text(), 'Vcode', 'Headers text is not equal to Vcode');
        
    }
    
    
            
    
        
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}