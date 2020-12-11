<?php
/**
 * php cli/Launcher.php job_name
 */

// Get the environment we are working on (dev | prod)
define('CURRENT_HOST', getenv('PHP_ENVIRONMENT'));
define('EXEC_TYPE', 'JOB');

// Include the main basic files (config, mailer and autoloader). Mailer need to be before
// to avoid breaking our autoloader trying to load their classes.
include_once('config/Defines.php');
include_once('application/swift-mailer/lib/swift_required.php');
include_once('application/Autoload.php');

// Add the main config flags for Jobs
date_default_timezone_set(APP_TIMEZONE);
ini_set('display_errors', APP_DEBUG);

try{

    // Get the job name
    $job_name = $argv[1];

    // If the job name is empty then throw exception and stop
    if (!$job_name) {
        throw new Exception("The job name is required!");
    }

    // Explode the name of the job and try t oload it as a php file
    $path = explode('_', $job_name);
    $path = APP_PATH.'/application/'.implode('/', $path).'.php';

    // If the file doesnt exists then throw exception and stop
    if (!file_exists($path)) {
        throw new Exception("The job file doesnt exists! ({$path})");
    }

    // If the class exists (autoloader worked) then create adn run the job
    if (class_exists($job_name)) {

        // Build the job class and run the job
        $job = new $job_name();
        $job->run();

    // If the class doesnt exists then throw exception
    } else {
        throw new Exception("The job class doesnt exists! ({$job_name})");
    }

} catch(Exception $e) {

    // Log in specific JOBS log
    Core_Logger::jobLog($e);

    // Write in stdin the error message (if job was executed from CLI then ignore msg)
    echo "{$job_name} failed to be executed due to {$e->getMessage()}";
}

