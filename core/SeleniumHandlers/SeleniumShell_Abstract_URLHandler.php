<?php

abstract class SeleniumShell_Abstract_URLHandler {
 
    private $_baseUrl = '';
    
    /**
     * Base urls. key === environment
     */
    private $_baseUrls;
    
    private $_uris;
       
    public function __construct( $env = SS_ENVIRONMENT ){
        $this->_baseUrls = $this->getBaseUrls();
        $this->_uris = $this->getUris();
        $this->_setBaseUrl( $env );
    }
    
    private function _setBaseUrlByEnvironment( $env ){
        if( array_key_exists($env, $this->_baseUrls) ){
            $this->_baseUrl = $this->_baseUrls[$env];
        } else {
            throw new ErrorException( 'Could not find the base url for the env: ' . SS_ENVIRONMENT );
        }
    }
    
    private function _setBaseUrl( $env ){
        $config = new ConfigHandler( PROJECTS_FOLDER . '/Dealit/config/project.ini' );
        if( $env ){
            $this->_setBaseUrlByEnvironment( $env );
        } else if($config->getAttribute('project-environment')) {
            $this->_setBaseUrlByEnvironment($config->getAttribute('project-environment'));
        } else {
            throw new ErrorException( 'No environment set, please check project.ini or use the --ss-env parameter' );
        }
    }
    
    public function getBaseURL(){
        return $this->_baseUrl;
    }

    public function getURL( $page ){
        if(array_key_exists($page, $this->_uris) ){
            return $this->getBaseURL() . $this->_uris[$page];
        }
        throw new ErrorException( 'page not found in _uris array' );
    }
    
    /**
     * @return array key should be environment, and value should be the base url.
     */
    abstract protected function getBaseUrls();
    /**
     * @return array key should be the page name, and value should be the uri adding the base url.
     */
    abstract protected function getUris();
}

