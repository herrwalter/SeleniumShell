<?php

$rel_path = substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))));


function __autoload( $className ){
    echo $className . ' not found.. ';
}

require_once( $rel_path. '/config/path-constants.php' );

require_once( CORE_HANDLERS_PATH . '/ProjectActionsInitiator.php' );
require_once( CORE_HANDLERS_PATH . '/ProjectHandlersInitiator.php' );

require_once( CORE_PATH . '/utils/Request.php' );
require_once( CORE_PATH . '/utils/Response.php' );


