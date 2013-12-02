<?php

$current_relative_path = substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))));

// Constants
define('CORE_CONFIG_PATH', $current_relative_path );
define('CORE_PATH', $current_relative_path . '/../');
define('CORE_SRC_PATH', $current_relative_path . '/../src/');
define('CORE_HANDLERS_PATH', $current_relative_path . '/../src/handlers/');
define('PHPUNIT' , 'C:\wamp\bin\php\php5.3.13\pear\PHPUnit\Autoload.php');