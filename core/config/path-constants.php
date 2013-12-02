<?php

$rel_path = substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))));

// Constants
define('CORE_CONFIG_PATH', $rel_path );
define('CORE_PATH', $rel_path . '/..');
define('CORE_SRC_PATH', $rel_path . '/../src');
define('CORE_HANDLERS_PATH', $rel_path . '/../src/handlers');
define('PROJECTS_FOLDER', CORE_PATH . '/../projects');