<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HtmlTestResult extends TestResult
{
    public function toString()
    {
        $testName = $this->getTestName();
        
        return '<div id="'.$testName.'" class="testblock" style="'
                . 'float:left;'
                . 'width:100px;'
                . 'height:100px;'
                . 'border:1px solid #000;'
                . 'word-break:break-all;'
                . 'background-color:'.$this->getBackgroundColor().';"'
                
                . ' >'.$testName.'</div>';
    }
    
    public function getBackgroundColor()
    {
        $exitcode = $this->getExitcode();
        if( $exitcode < 0 ){
            return '#ffffff';
        } elseif( $exitcode === 0){
            return '#00ff00';
        } else {
            return '#ff0000';
        }
    }
}
