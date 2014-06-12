<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControllerFileScanner extends FileScanner {
    
    protected function validateFile( $file )
    {
        $filename = pathinfo($file, PATHINFO_FILENAME);
        return substr_compare($filename, 'Controller', -10) === 0 && $filename !== 'Controller';
    }
    
    public function getFileNames(){
        $files = $this->getFilesInOneDimensionalArray();
        $filenames = array();
        foreach($files as $file ){
            $filenames[] = pathinfo($file, PATHINFO_FILENAME);
        }
        return $filenames;
    }
}
    