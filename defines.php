<?php
// Common config between environments
// Titles and names
define('APP_NAME'       , 'Base project');

// App Run Mode and paths
define('APP_LAYOUT' , 'layout');

// If the execution is from the app in a browser
if (EXEC_TYPE == 'WEB') {

    // Get the basic url and path from the request
    define('APP_URL'  , "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/"); //http: //*******/
    define('APP_PATH' , $_SERVER['DOCUMENT_ROOT']);
} else {

    // If not from the web but from a job then get the folder path from PWD datapoint and
    // config the path as the production one (Cannot get the web url if from a Job so always
    // point mails to production url)
    define('APP_URL'  , 'https://courier.triadify.com/'); //http: //*******/
    define('APP_PATH' , $_SERVER['PWD']);
}

// Check the current host
switch (CURRENT_HOST) {

    // Development Environment
    case 'dev':

        // Database Configuration
        define('DB_TYPE' , 'mysql');
        define('DB_HOST' , '127.0.0.1');
        define('DB_NAME' , 'test');
        define('DB_USER' , 'test');
        define('DB_PASS' , 'test');

        // App Run Mode and paths
        define('APP_DEBUG'    , true);
        define('APP_TIMEZONE' , 'America/New_York');
        break;

    default:
        throw new Exception("This environment is not supported! Please add environment var to the server.");
        break;
}
