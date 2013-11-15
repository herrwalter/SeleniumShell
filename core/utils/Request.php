<?php


class Request
{
    protected $_req;
    
    protected $_options = array( 
        CURLOPT_RETURNTRANSFER => true,         // return web page 
        CURLOPT_HEADER         => true,         // don't return headers
        CURLINFO_HEADER_OUT => true
    );
    
    public function __construct( $method = '', $url = '', $parameters = array(), $headers = array() ){
        $this->_req = curl_init($url);
        $this->setMethod($method);
        $this->setParameters($parameters);
        $this->setRequestHeaders($headers);
    }
    
    public function setMethod( $method ){
        $this->_options[CURLOPT_CUSTOMREQUEST] = $method;
    }
    
    public function setRequestHeaders( $array ){
        $this->_options[CURLOPT_HTTPHEADER] = $array;
    }
    
    public function setParameters( $array ){
        $this->_options[CURLOPT_POSTFIELDS] = $array;
    }
    
    public function setCookies( $array ){
        foreach( $array as $cookie ){
            if( strpos('=', $cookie) == -1 ){
                throw new Exception('Cookie: '.$cookie.' has no "=" in name.');
            }
        }
        $this->_options[CURLOPT_COOKIE] = implode(';', $array);
        
    }
    
    public function execute(){
        curl_setopt_array($this->_req, $this->_options);
        $response = curl_exec($this->_req);
        $info = curl_getinfo($this->_req);
        curl_close($this->_req);
        
        return new Response( $response, $info);
       
    }
    
    
}

