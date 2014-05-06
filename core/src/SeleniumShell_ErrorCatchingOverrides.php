<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SeleniumShell_ErrorCatchingOverrides extends SeleniumShell_Asserts {
    
    /**
     * Select your element by Id 
     * @param type $id
     */
    public function byId( $id ){
        try{
            $returnValue = parent::byId($id);
        } catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e ){
            switch($e->getCode()){
                case 7: 
                    throw new ErrorException( 'could not find element with: ' . $id . ' on page: ' . $this->url() );
                default:
                    echo '';
                    break;
            }
        }
        return $returnValue;
    }
    
    public function byCssSelector($cssSelector)
    {
        try{
            $returnValue = parent::byCssSelector($cssSelector);
        } catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e ){
            switch($e->getCode()){
                case 7: 
                    throw new ErrorException( 'could not find element(s) with: ' . $cssSelector . ' on page: ' . $this->url() );
                default:
                    echo '';
                    break;
            }
        }
        return $returnValue;
    }
    
}