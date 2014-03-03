<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DealitchromeLoginTest extends SeleniumShell_Test {

	public $browser = "chrome";

    
    protected $links = array(
        'login' => array( 'acceptance' => 'https://www-acceptance.deal-it.com/login/' )
    );
    /* @var _urlHandler URLHandler */
    protected $_urlHandler;

    public function __construct( ){
        
       $this->_urlHandler = new URLHandler( );
       var_dump( 'wat nou, niet aangeroepen?' );
    }
    
    /**
     * @ss-browsers firefox,chrome
     */
    public function testLogin(){
        $login = new LoginController();
        $user = $login->getRandomUserByFunction('Algemeen directeur');
        
        $this->url( $this->_urlHandler->getURL('login') );
        
        $formHandler = new FormHandler( $this->byCssSelector('form'), $this );
        $formHandler->mapValuesToElementsById(array(
            'user' => $user['email'],
            'vcode' => $user['vcode'],
            'pass' => $user['password']
        ));
        $formHandler->submit();
    }
    
}