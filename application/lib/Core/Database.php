<?php

use \PDO as PDO;

class Core_Database extends PDO {

    public function __construct(){

        // Set DB values to configure the conection
        $config[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";
        $config[PDO::MYSQL_ATTR_FOUND_ROWS]   = true;

        // Build a new PDO connection with config definitions
        parent::__construct(DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, $config);
    }

}

