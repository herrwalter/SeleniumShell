<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Log
{

    protected $_log = '';
    protected $_file = '';

    public function __construct($file = '')
    {
        $this->_setFile($file);
    }

    protected function _setFile($file)
    {
        if (!file_exists($file)) {
            file_put_contents($file, $this->_log);
        }
        $this->_file = $file;
    }

    public function getLog()
    {
        return file_get_contents($this->_file);
    }
    
    public function write($text)
    {
        file_put_contents($this->_file, $text, FILE_APPEND);
    }

    public function overwrite($text)
    {
        file_put_contents($this->_file, $this->_log);
    }

}
