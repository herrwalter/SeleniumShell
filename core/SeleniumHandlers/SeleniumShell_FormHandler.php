<?php


class SeleniumShell_FormHandler {
    
    protected $_elements;
    
    /** @var PHPUnit_Extensions_Selenium2TestCase  */
    protected $_session;
    
    /** @var PHPUnit_Extensions_Selenium2TestCase_Element */
    protected $_form;
    
    /** @var SeleniumShell_FormHelpers */
    protected $_helpers;
    
    /**
     * @param PHPUnit_Extensions_Selenium2TestCase $session
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $form
     */
    public function __construct( $session, $form = false )
    {
        $this->_setSession( $session );
        $this->_helpers = new SeleniumShell_FormHelpers( $this->_session );
        $this->_setForm( $form );
    }
    /**
     * @desc Sets the current Test context
     * @param PHPUnit_Extensions_Selenium2TestCase $session
     */
    protected function _setSession( $session )
    {
        $this->_session = $session;
    }
    /**
     * @desc Sets form, if form is false, it will select the first form found on the page.
     */
    protected function _setForm( $form ){
        if( !$form ){
            $form = $this->_session->byTag('form');
        }
        $this->_form = $form;
    }
    /**
     * @desc Set element to the elements array.
     *       Selects by Id.
     * @param type $id
     */
    protected function _setElementById( $id )
    {
        try{
            $this->_elements[ $id ] = $this->_session->byId( $id );
        }
        catch(Exception $e ){
            $this->_elements[ $id ] = false;
            var_dump( 'Element with id: ' . $id . ' is not found.' );
        }
    }
    /**
     * @desc Set element to the elements array
     *       Selects by Name. Will be the first occurance
     * @param type $name
     */
    protected function _setElementByName( $name )
    {
        try{
            $this->_elements[ $name ] = $this->_session->byId( $name );
        }
        catch(Exception $e ){
            $this->_elements[ $name ] = false;
            var_dump( 'Element with name: ' . $name . ' is not found.' );
        }
    }
    /**
     * @desc Gets the element type. 
     *       Most of the time it will just be the
     *       tag name but for the input we need the
     *       type of the element.
     * 
     * @param type $element
     * @return string
     */
    protected function _getElementType( $element )
    {
        $tagName = $element->name();
        switch( $tagName )
        {
            case 'input':
                if( $element->attribute('type') !== 'text'){
                    return $element->attribute('type');
                }
                return 'text';
                break;
            default:
                return $tagName;
                break;
        }            
    }
    /**
     * @desc Sets the element based on its type
     *       with the provided value.
     * 
     * @param type $key key value as selected and saved in the elements array
     * @param type $value value to set on the element.
     */
    protected function _setElementValue( $key, $value )
    {
        $element = $this->_elements[$key];
        if( $element ){ // element could not be found and will be marked as false.
            switch( $this->_getElementType( $element ) )
            {
                case 'select':
                    $select = $this->_session->select( $element );
                    if( $value == 'random' ){
                        $this->_helpers->changeSelectboxWithRandomOption( $select );
                    }
                    else{
                        $select->selectOptionByValue($value);
                    }
                    break;
                case 'radio':
                case 'checkbox':
                    // if the state of the checkbox is not equal to the value
                    // we should change the state.
                    if( $element->selected() && !$value ){ // checkbox is checked and value wants it to uncheck
                        $element->click();
                    }
                    elseif( !$element->selected() && $value ){ //checkbox is not checked and it should
                        $element->click();
                    }
                    // in other cases were the checkbox is selected and should be and otherwise 
                    // we will leave the checkbox alone..
                    break;
                case 'text':
                case 'textarea':
                case 'password':
                    $element->clear();
                    $element->value($value);
                    break;
                case 'file':
                    $element->value($value);
                    break;
                default:
                    var_dump( 'There is no method for the ElementTag: ' . $this->_elementTypes[$key] );
                    break;
            }
        }
    }
    /**
     * @desc Sets all the elements based on Id selector
     * @param idsVsValues Array the key needs to be the ID, value needs to be the value to set.
     */
    public function mapValuesToElementsById( $idsVsValues )
    {
        foreach( $idsVsValues as $id => $value ){
            $this->_setElementById($id);
            $this->_setElementValue( $id, $value );
        }
    }
    /**
     * @desc Sets all the elements based on the Name selector, first name to found will be set.
     * @param namesVsValues Array the key needs to be the name , value needs to be the value to set.
     */
    public function mapValuesToElementsByName( $namesVsValues )
    {
        foreach( $namesVsValues as $name => $value ){
            $this->_setElementById( $name );
            $this->_setElementValue( $name, $value );
        }
    }
    
    /**
     * @desc Will submit the form.
     */
    public function submitForm()
    {
        $this->_form->submit();
    }
    
    /**
     * @desc will set new form and delete the references to the current elements
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $form
     */
    public function setNewForm( $form = false ){
        $this->_setForm( $form );
        $this->_elements = array();
    }
    
}