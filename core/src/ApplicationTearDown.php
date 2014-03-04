<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'C:\php\pear\Mail.php';

class ApplicationTearDown extends PHPUnit_Framework_TestSuite {
    
    public static function suite()
    {
        return new ApplicationTearDown( 'SeleniumShell' );
    }
    
    protected function tearDown(){
        $subject = 'SeleniumShell AutomatedTests';
        $body = 'De selenium testen zijn klaar.. ';
        

        $config = new ConfigHandler(CORE_CONFIG_PATH . '/config.ini');
        if( $config->isParameterSet('--ss-mail-results') ){
            $to = $config->getParameter('--ss-mail-results');
        } else {
            return;
        }
        echo( 'Mailing results to: ' . $to );
        
        $headers = array(
            'From' => 'Wouter Wessendorp <herrwalter@gmail.com>',
            'To' => '<'.$to.'>',
            'Subject' => $subject,
        );
        
        $smtp  = Mail::factory(
            'smtp', array(
            'host' => 'ssl://smtp.gmail.com',
            'port' => '465',
            'auth' => true,
            'username' => 'herrwalter@gmail.com',
            'password' => ''
        ));
        
        $mail = $smtp->send($to,$headers,$body );
        if (PEAR::isError($mail)) {
            echo($mail->getMessage());
        } else {
            echo('Message successfully sent!');
        }
    }
}
