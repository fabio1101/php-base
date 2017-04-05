<?php
namespace MVC;

use \PDO;

class Database extends \PDO {

    public function __construct(){

        $config[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";
        $config[PDO::MYSQL_ATTR_FOUND_ROWS]   = true;

        parent::__construct( DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME,DB_USER , DB_PASS, $config);
    }

}

