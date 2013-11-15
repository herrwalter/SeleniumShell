<?php

class Response
{
    protected $info;
    protected $body;
    protected $header;
    protected $request_headers;
    protected $response;
    
    public function __construct( $response, $info )
    {
        
        $this->info = $info;
        $this->response = $response;
        $this->_setHeader();
        $this->_setBody();
        $this->_setRequestHeaders();
    }
    protected function _setRequestHeaders(){
        $array = explode("\r\n", $this->info['request_header']);
        $this->request_headers = $array;
        
    }
    
    protected function _setHeader(){
        $header = substr($this->response, 0, $this->info['header_size']);
        $this->header = $header;
    }
    
    protected function _setBody(){
        $body = substr($this->response, strlen($this->header)-$this->info['download_content_length'] -1);  
        $this->body = $body;
    }
    
    public function getBody(){
        return $this->body;
    }
    
    public function getJson(){
        return json_decode($this->body);
    }
    /*
    public function getText(){
        return str_replace("\n",'', strip_tags($this->body));
    }*/
    
    public function getStatusCode(){
        return $this->info['http_code'];
    }
    
}