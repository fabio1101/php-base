<?php
// Common config between environments
// Database Configuration
define('DB_TYPE' , 'mysql');
define('DB_HOST' , '127.0.0.1');

// Titles and names
define('APP_NAME' , 'Executive Courier Systems');
define('APP_NAME_SHORT' , 'ECS');

// App Run Mode and paths
define('DEFAULT_CONTROLLER' , 'Index.php'); // Allways ucfirst
define('DEFAULT_ACTION'     , 'Index');
define('DEFAULT_LANGUAGE'   , 'en');
define('ALLOWED_LANGUAGES'  , '["EN"]'); // JSON format
define('APP_CONTROLLERS'    , 'controllers');
define('APP_ACTION'         , 'Action');
define('APP_LOGFILE'        , 'Logger.txt');
define('APP_JOBLOGFILE'     , 'JOB_Log.txt');
define('APP_SQLLOGFILE'     , 'SQL_Log.txt');
define('APP_DEBUGFILE'      , 'debug.txt');
define('APP_LAYOUT'         , 'layout');

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
        define('DB_NAME' , 'dev_courier');
        define('DB_USER' , 'developers');
        define('DB_PASS' , '44fGtkRf2qXxREh');

        // Tags to replace in the layout for google tag
        define('GTAG_HEADER', '');
        define('GTAG_BODY', '');

        // App Run Mode and paths
        define('APP_DEBUG'    , true);
        define('TERM_DEBUG'   , false);
        define('APP_TIMEZONE' , 'America/New_York');

        // Mail Config
        define('M_SERVER'       , 'smtp.gmail.com');
        define('M_USERNAME'     , 'fabio.valencia@techandservices.net');
        define('M_EMAIL'        , 'fabio.valencia@techandservices.net');
        define('M_NAME'         , 'Triadify test mailer');
        define('M_PASSWORD'     , 'favasu1101');
        define('M_PORT'         , '465');//'587');
        define('M_CONN'         , 'ssl');//'tls');
        break;

    // Production Environment
    case 'prod':
        // Database Configuration
        define('DB_NAME' , '');
        define('DB_USER' , '');
        define('DB_PASS' , '');

        // Tags to replace in the layout for google tag
        define('GTAG_HEADER', '');
        define('GTAG_BODY', '');

        // App Run Mode and paths
        define('APP_DEBUG'    , false);
        define('TERM_DEBUG'   , false);
        define('APP_TIMEZONE' , 'America/New_York');

        // Mail Config
        define('M_SERVER'       , 'smtp.gmail.com');
        define('M_USERNAME'     , 'triadify@triadify.com');
        define('M_EMAIL'        , 'triadify@triadify.com');
        define('M_NAME'         , 'Triadify Inc');
        define('M_PASSWORD'     , 'Triadify2017*');
        define('M_PORT'         , '465');//'587');
        define('M_CONN'         , 'ssl');//'tls');
        break;

    default:
        throw new Exception("This environment is not supported! Please add environment var to the server.");
        break;
}
