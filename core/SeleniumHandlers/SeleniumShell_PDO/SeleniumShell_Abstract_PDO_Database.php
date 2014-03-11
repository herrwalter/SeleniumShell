<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class SeleniumShell_Abstract_PDO_Database{
    private $_host;
    private $_user;
    private $_pass;
    private $_dbname;
 
    /** @var _dbh PDO */
    private $_dbh;
    private $_error;
    private $_statement;
 
    public function __construct(){
        
        $this->_setDatabaseName();
        $this->_setHost();
        $this->_setPassword();
        $this->_setUser();
        $this->_connectToDatabase();
        
    }
    
    private function _connectToDatabase(){
        $dsn = 'mysql:dbname=' . $this->_dbname . ';host=' . $this->_host;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instanace
        try{
            $this->_dbh = new PDO($dsn, $this->_user, $this->_pass, $options);
        }
        // Catch any errors
        catch(PDOException $e){
            $this->_error = $e->getMessage();
        }        
    }
    
    public function showErrors(){
        var_dump($this->_error);
    }
    
    public function query( $query ){
        $this->_statement = $this->_dbh->prepare($query);
    }
    
    public function execute(){
        $this->_statement->execute();
    }
    
    public function results(){
        $this->execute();
        return $this->_statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function single(){
        $this->execute();
        return $this->_statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function lastInsertedId(){
        $this->execute();
        return $this->_statement->lastInsertedId();
    }
    
    public function rowCount(){
        $this->_statement->rowCount();
    }
    
    private function _setHost(){
        $this->_host = $this->getHost();
    }
    private function _setUser(){
        $this->_user = $this->getUser();
    }
    private function _setPassword(){
        $this->_pass = $this->getPassword();
    }
    private function _setDatabaseName(){
        $this->_dbname = $this->getDatabaseName();
    }


    abstract protected function getHost();
    abstract protected function getUser();
    abstract protected function getPassword();
    abstract protected function getDatabaseName();
    
}