<?php
// Get the environment we are working on (dev | prod)
define('CURRENT_HOST', getenv('PHP_ENVIRONMENT'));
define('EXEC_TYPE', 'WEB');

// Include basic Libraries
include_once('config/Defines.php');
include_once('application/swift-mailer/lib/swift_required.php');
require('application/smarty/Smarty.class.php');
include_once('application/Autoload.php');

// Include 3rParty Libraries

// PHP definitions
date_default_timezone_set(APP_TIMEZONE);
ini_set('display_errors', APP_DEBUG);

// Init bootstrap
try {

    // Build bootstrap and call controller/action
    $bootstrap = new Core_Bootstrap();
    $bootstrap->init();

} catch (Exception $e) {

    // Log values to default logger file (APP_LOGFILE)
    Core_Logger::log($e);

    // Stop the app and write a message. If APP_DEBUG is true will write the error, if not will write Error msg.
    die((APP_DEBUG)?
        '<pre>'.$e->getMessage().' - '.$e->getFile().' - '.$e->getLine().'</pre>':
        '<h1>System Error: please contact support</h1>');
}
