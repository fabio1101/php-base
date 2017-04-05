<?php
/****************************************************************************/
// Database Configuration
define('DB_TYPE','mysql');
define('DB_HOST','127.0.0.1');
define('DB_NAME','boxes');
define('DB_USER','boxes_root');
define('DB_PASS','boxes.testing.bd_apps');
/****************************************************************************/
// Titles and names
define('APP_NAME'      ,'Casilleros');
/****************************************************************************/
// App Run Mode and paths
define('__DEFAULT_CONTROLLER__','index');
define('APP_KEY'        , 'ZXCSWQPLI..__**QWRETY');
define('APP_URL'        , 'http://boxes.testing.com/'); //http://*******/
define('APP_PATH'       , '/var/www/html/boxes');
define('APP_DEBUG'      , TRUE);
define('APP_DEBUGMODE'  , "E_ERROR ^ E_NOTICE");
define('APP_LOGFILE'    , 'Logger.txt');
define('APP_SQLLOGFILE' , 'SQL_Log.txt');
define('APP_VIEWS'      , 'views');
define('APP_MODELS'     , 'models');
define('APP_CONTROLLERS', 'controllers');
define('APP_METHODS'    , 'Action');
define('APP_PUBLIC'     , 'public');
define('APP_LANGUAGE'   , 'ES');
define('APP_TIMEZONE'   , 'America/New_York');
/****************************************************************************/
// Var config for each client, implement config table with join to company table
// SMTP Mail account
define('M_SERVER'       , 'smtp.gmail.com');
define('M_USERNAME'     , 'fabio.valencia@techandservices.net');
define('M_EMAIL'        , 'fabio.valencia@techandservices.net');
define('M_NAME'         , 'Boxes Company Name Test');
define('M_PASSWORD'     , 'favasu1101');
define('M_PORT'         , '465');//'587');
define('M_CONN'         , 'ssl');//'tls');
