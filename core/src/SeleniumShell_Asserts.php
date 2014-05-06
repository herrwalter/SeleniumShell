<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SeleniumShell_Asserts extends SeleniumShell_HelperMethods{
    
    
    public function assertElementExists( $selector, $message = ' could not find the element with given selector'){
        $element = $this->selectElementsByCssSelector($selector);
        $this->assertTrue( count($element) !== 0, $message );
    }
    
    public function assertElementNotExists( $selector, $message = '' ){
        $element = $this->selectElementsByCssSelector($selector);
        $this->assertTrue( count($element) === 0, $message );
    }
    
    public function assertElementText( $element, $text, $message ){
        $this->assertEquals($text, $element->text(), $message);
    }
    
    /**
     * Will assert is an stylesheet is embedded.
     * 
     * @param type $styleSheetName
     */
    public function assertStyleSheetsIsEmbedded( $styleSheetFileName ){
        $links = $this->selectElementsByCssSelector('link');
        $styleSheetFound = false;
        // if .css is not added to the stylesheetname. we will add it for them..
        if( !preg_match( '/.css$/',$styleSheetFileName )){
            $styleSheetFileName = $styleSheetFileName.'.css';
        }
        foreach( $links as $link ){
            $href = $link->attribute('href');
            $rel = $link->attribute('rel');
            if(preg_match('/'. $styleSheetFileName .'$/', $href) && strtolower($rel) === 'stylesheet' ){
                $styleSheetFound = true;
            }
        }
        $this->assertTrue( $styleSheetFound, 'Stylesheet ' . $styleSheetFileName . ' is not found.' );
    }
    /**
     * Asserts the attribute's value of an element
     * @param type $selector cssSelector
     * @param type $attribute attributeKey
     * @param type $value attributeValue
     */
    public function assertElementsAttributeEqualsValueByCssSelector($selector, $attribute, $value ){
        $element = $this->selectElementsByCssSelector($selector);
        if( count($element) == 0){
            $this->fail('The selector provided, could not select an element');
        } else if( count($element) > 1 ){
            $this->fail('Selector found more than one element. Adjust your selector so there is only one result');
        }
        $attributeValue = $element[0]->attribute( $attribute );
        $this->assertEquals( $attributeValue, $value );
    }
    
    public function assertElementsAttributeEqualsValueByElement($element, $attribute, $value ){
        if( !$element instanceof PHPUnit_Extensions_Selenium2TestCase_Element ){
            $this->fail( 'Element is not an instance of PHPUnit_Extensions_Selenium2TestCase_Element' );
        }
        $attributeValue = $element->attribute( $attribute );
        $this->assertEquals( $attributeValue, $value );
    }
    
    public function assertInArray($value, $array, $message = ''){
        $this->assertTrue(array_search($value, $array) !== false, $message);
    }
}