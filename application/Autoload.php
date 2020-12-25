<?php
/**
 * Autoloader for all classes. Files needs to be in one directory (application/lib/) and needs
 * to start each folder after that with uppercase first letter (Example_Of_Class_Name)
 */

// Register only php files
spl_autoload_extensions(".php");

spl_autoload_register(
    function($class_name)
    {
        // Explode by _ to get the path to file
        $filepath = explode('_', $class_name);

        // Base folder path where all classes will be loaded
        $url = 'application/lib/';

        // Check if the autoload is called for a Job class (All of them starts with Jobs_)
        if ($filepath[0] == 'Jobs') {

            // If this is a job change the url main path from controllers path to jobs path
            $url = 'application/Jobs/';

            // Remove the first folder because Im already inside it (Jobs folder)
            unset($filepath[0]);
        }

        // Glue array for path into string, make sure all folders after libs start with uppercase (only first letter)
        $url .= implode('/', array_map(function($element){
            return ucfirst($element);
        }, $filepath));

        // Add php extention
        $url .= '.php';

        // Check first if file exists
        if (file_exists(APP_PATH.'/'.$url)) {

            // If exists then load the file
            require_once($url);

        } else {

            // Throw exception to avoid
            throw new Exception('Class File doesnt exists. The name provided was: "'.$class_name.'"');
        }
    }
);
