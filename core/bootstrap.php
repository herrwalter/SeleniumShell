<?php

$current_relative_path = substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))));

define( 'PROJECT_NAME', dirname(dirname(__FILE__)) );


require_once( $current_relative_path . '/../../../core/config/constants.php' ); //get constants paths
require_once( PHPUNIT ); // get PHPUnit

require_once( CORE_PATH . '/src/SeleniumShell.php' ); // get SeleniumShell

require_once( CORE_HANDLERS_PATH . '/ProjectActionsInitiator.php' );
require_once( CORE_HANDLERS_PATH . '/ProjectHandlersInitiator.php' );

require_once( CORE_PATH . '/utils/Request.php' );
require_once( CORE_PATH . '/utils/Response.php' );


