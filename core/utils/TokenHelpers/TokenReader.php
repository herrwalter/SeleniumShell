<?php

/**
 * Will give you checks on the token you
 * provide.
 * 
 */
class TokenReader 
{
    private $_token;
    
    public function __construct( $token )
    {
        $this->_token = $token;
    }        
    /**
     * Checks if the token is a curly open
     * @return boolean
     */
    public function isCurlyOpen()
    {
        return is_string($this->_token) && $this->_token == '{';
    }
    /**
     * Checks if token is a curly close
     * @return boolean
     */
    public function isCurlyClose()
    {
        return is_string($this->_token) && $this->_token == '}';
    }
    /**
     * Checks if type is a Class
     * @return boolean
     */
    public function isClassDefinition()
    {
        return is_array($this->_token) && $this->_token[0] === T_CLASS;
    }
    
    /**
     * Is a Method
     * @return type
     */
    public function isMethod()
    {
        return is_array($this->_token) && $this->_token[0] == T_FUNCTION;
    }
    /**
     * Checks if the type is a variable
     * @return boolean
     */
    public function isVariable()
    {
        return is_array($this->_token) && $this->_token[0] === T_VARIABLE;
    }
    /**
     * Is documentation block
     * @return type
     */
    public function isDocumentationBlock()
    {
        return is_array($this->_token) && $this->_token[0] === T_DOC_COMMENT;
    }
    /**
     * Gets the annotations from a doc block
     * @param string $doc
     * @return array with annotations
     */
    public function getAnnotations()
    {
        if( $this->isDocumentationBlock() ){
            preg_match_all('#@(.*?)\n#s', $this->_token[1], $annotations);
            $niceFormat = array();
            if( count($annotations) > 1){
                foreach( $annotations[1] as $annotation){
                    $anno = explode(' ', $annotation);
                    if( count($anno) === 1){
                        $anno[] = true;
                    }
                    $niceFormat[$anno[0]] = $anno[1];
                }
            }
            return $niceFormat;
        }
        return;
    }    
    /**
     * Gets the string from the token;
     */
    public function getString()
    {
        if( is_array($this->_token) ){
            return $this->_token[1];
        }
        return $this->_token;
    }
    /**
     * Gets the line number of the token
     * @return mixed integer if accessible, false if not
     */
    public function getLineNumber()
    {
        $token = $this->_token;
        if( is_array( $this->_token )){
            return $this->_token[2];
        }
        return false;
    }
}