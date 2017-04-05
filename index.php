<?php
/**********************************************/
// Include basic Libraries
include_once('config/Defines.php');
include_once('libs/mvc/Bootstrap.php');
//include_once('libs/core/Customcode.php');
include_once('libs/Autoload.php');
// Include 3rParty Libraries
/**********************************************/
require_once 'libs/dompdf/dompdf_config.inc.php';

// PHP definitions
date_default_timezone_set(APP_TIMEZONE);

$MyLibs = array( 'MVC' 			=> 'libs/mvc',
 		 		 'CORE'			=> 'libs/core',
				 'model'		=> 'models',
				 'inner'		=> 'libs/inner');
foreach( $MyLibs as $key => $value ){
	 // echo $value .'==>'.PHP_EOL;
		new Autoload( $value );	
}
unset($MyLibs);

try {
    $bootstrap = new MVC\Bootstrap();
    $bootstrap->setPathRoot( @getcwd() . '/' ); 
    $bootstrap->setControllerDefault( __DEFAULT_CONTROLLER__ );
    $bootstrap->init();
} catch (\Exception $e) {
    @file_put_contents(APP_PATH.'/'.APP_PUBLIC.'/'.APP_LOGFILE, 
        date('Ymd_his') . ' - ' . $e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine(). PHP_EOL, FILE_APPEND);
    die( (APP_DEBUG) 
        ? '<pre>' . $e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine(). '</pre>'
        :'System Error: please contact support');
}
 /**********************************************/
