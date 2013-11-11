<?php

$current_relative_path = substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))));

define( 'PROJECT_NAME', dirname(dirname(__FILE__)) );

require( $current_relative_path . '/../../../core/config/constants.php' ); //get constants paths
require( PHPUNIT ); // get PHPUnit
require( CORE_PATH . '/src/SeleniumShell.php' ); // get SeleniumShell
require( CORE_HANDLERS_PATH . '/ProjectActionsInitiator.php' );
require( CORE_HANDLERS_PATH . '/ProjectHandlersInitiator.php' );



