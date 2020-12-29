<?php
// Get the environment we are working on (dev | prod)
define('CURRENT_HOST', getenv('PHP_ENVIRONMENT'));
define('EXEC_TYPE', 'WEB');

// Include basic Libraries
include_once('defines.php');
require('vendor/autoload.php');
include_once('application/Autoload.php');

// PHP definitions
ini_set('display_errors', APP_DEBUG);

// Init bootstrap
try {

    // Url object will receive the url and process it to get target controller/action and
    // values to send to function call
    $url = new Core_Bootstrap_Url($_GET['url'] ?? '');

    $launcher = new Core_Bootstrap_Launcher();
    $launcher->launch($url);

} catch (Throwable $e) {

    // Stop the app and write a message. If APP_DEBUG is true will write the error, if not will write Error msg.
    die((APP_DEBUG)?
        '<pre>'.$e->getMessage().' - '.$e->getFile().' - '.$e->getLine().'</pre>':
        '<h1>System Error: please contact support</h1>');
}
