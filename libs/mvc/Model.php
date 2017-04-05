<?php

namespace MVC;

use \PDO;

class Model {
	public $db; // Db Conexion Class
	public $db_Sql; // Sql Connection Instance from Cla_Conexion::getInstancia
	public $db_Debug; // Debug Mode Default FALSE
	public $table;
	function __construct() {
		$this->db = new Database ();
	}
	
	public function Debug() {
		if ($this->db_Debug) {
			echo '<pre>';
			print_r ( $this->db_Sql );
			echo '</pre>';
			die ( "-=========== DEBUG INFO ===========-" );
		}
	}
	
	public function getQuery($sql, $isSel = true) {
        try{
    		$this->db_Sql = "BEGIN";
    		$stmt = $this->db->prepare ( $this->db_Sql );
    		$stmt->execute ();

    		$this->db_Sql = $sql;
    		$this->Debug ();
    		$stmt = $this->db->prepare ( $this->db_Sql );
    		$stmt->setFetchMode ( PDO::FETCH_ASSOC );
    		$estado = $stmt->execute ();
    		if (strpos($this->db_Sql, 'INSERT') !== false){
    			$id = $this->db->lastInsertId();
    		}elseif (strpos($this->db_Sql, 'UPDATE') !== false){
    			$id = $stmt->rowCount();
    		}elseif (strpos($this->db_Sql, 'DELETE') !== false){
    			$id = $stmt->rowCount();
    		}else{
    			$id = 0;
    		}
    		$cadenaTmp = $stmt->fetchAll ();
    		$this->db_Sql = "COMMIT";
    		$stmt = $this->db->prepare ( $this->db_Sql );
    		$stmt->execute ();
    		
    		$stmt = NULL;
    		if ($isSel) {
    			return $cadenaTmp;
    		} else {
    			return $id;
    		}
        } catch (Exception $e){

            @file_put_contents(APP_PATH.'/'.APP_PUBLIC.'/'.APP_SQLLOGFILE,
                date('Ymd_his') . ' - ' . $e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine(). PHP_EOL, FILE_APPEND);
        }
	}
} 
