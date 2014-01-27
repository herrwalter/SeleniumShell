<?php

/**
 * Will keep track if you are still in the same closure 
 * while iterating file tokens.
 */
class TokenClosure
{
    /** @var TokenReader */
    protected $_tokenRdr;
    protected $_opens = 0;
    protected $_closes = 0;
    protected $_inClosure = false;
    protected $_done = false;
    protected $tracking = false;
    protected $track = '';
    protected $name = '';
    
    public function __construct( $name ){
        $this->name = $name;
    }
    
    public function track( $token )
    {
        if( $this->tracking ){
            $this->_tokenRdr = new TokenReader($token);
            $this->_addCurlyOpen();
            $this->_addCurlyClose();
            $this->_isInClosure();
            $this->_addTrack();
        }
    }
    
    protected function _addCurlyOpen()
    {
        if( $this->_tokenRdr->isCurlyOpen() ){
            $this->_opens++;
        }
    }
    
    protected function _addCurlyClose()
    {
        if( $this->_tokenRdr->isCurlyClose() ){
            $this->_closes++;
        }       
    }
    
    protected function _addTrack(){
        $this->track .= $this->_tokenRdr->getString();
    }
    
    protected function _isInClosure(){
        if( $this->_opens > 0 && $this->_opens > $this->_closes ){
            $this->_inClosure = true;
        } else if($this->_opens > 0 && $this->_opens == $this->_closes) {
            $this->_done = true;
            $this->_tracking = false;
            $this->_inClosure = false;
        }
    }
    
    public function startTracking(){
        $this->tracking = true;
    }
    
    public function inClosure(){
        return $this->_inClosure;
    }
    
    public function isDone(){
        return $this->_done;
    }
    
    public function getTrack(){
        return $this->track;
    }
    
    public function reset(){
        $this->_closes = 0;
        $this->_opens = 0;
        $this->track = '';
        $this->tracking = false;
        $this->_done = false;
    }
}