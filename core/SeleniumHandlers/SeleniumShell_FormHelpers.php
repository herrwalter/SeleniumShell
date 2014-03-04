<?php

class SeleniumShell_FormHelpers{
    private $_session;
    
    /* @var FunctionalHelpers*/
    public $helpers;
    /**
     * 
     * @param PHPUnit_Extensions_Selenium2TestCase $session
     */   
    public function __construct( $session ){
        $this->_session = $session;
        $this->helpers = new FunctionalHelpers($session);
    }
    
    private function _getTagNameCode($attribute, $attributeValue ){
        switch( $attribute ){
            case 'id':
                return 'return document.getElementById("' . $attributeValue . '").tagName';
                break;
            case 'name':
                return 'return document.getElementsByName("' . $attributeValue . '")[0].tagName';
                break;
        }
    }
    
    public function getElementsTagName( $element, $attribute ){
        $attributeValue = $element->attribute( $attribute );
        $result = $this->_session->execute(array(
            'script' => $this->_getTagNameCode( $attribute, $attributeValue),
            'args' => array(),
        ));
        
        return $result;
    }
    
    
    public function changeSelectboxWithRandomOption( $select )
    {
        $currentSelection = $select->selectedLabel();
        $possibleOptions = $select->selectOptionLabels();
        
        
        // delete the selected item
        if( $currentSelection !== 'Selecteer een optie' ){
            unset($possibleOptions[array_search($currentSelection, $possibleOptions)]);
        }
        // delete 'Selecteer een optie';
        unset($possibleOptions[array_search('Selecteer een optie', $possibleOptions)]);
        
        //array_pop($possibleOptions);
        
        $randomPossibleOption = $this->helpers->getRandomArrayValue($possibleOptions);
        //select a random possible option.
        
        $select->selectOptionByLabel($randomPossibleOption);
        $this->_session->assertNotEquals($randomPossibleOption, $currentSelection, 'No different option selected.');
        
        return $randomPossibleOption;
    }
    
}