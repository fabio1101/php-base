<?php

namespace model;

use MVC\Model;
use MVC\Utils;
use CORE\Config;
use CORE\Session;

class IndexModel extends Model
{
    public function __construct($Debug = FALSE){
        parent::__construct();
        $this->db_Debug = $Debug;
    }

    function getCities(){
        $sql = "SELECT city.id, getCityFullName(city.id) as name
                FROM city
                ORDER BY name;";
        return $this->getQuery($sql);
    }

    function setRequestCode($usr_id, $code){
        $timestamp = date('Y-m-d h:i:s', time()+(24*60*60));
        $sql = "UPDATE user SET request = '$code', datetime_request = '$timestamp' WHERE id = '$usr_id';";
        return $this->getQuery($sql, false);
    }
}
