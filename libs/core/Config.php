<?php

namespace CORE;

use MVC\Model;
use MVC\Utils;

class Config {
	public $companyId;
	private $data;
	private $model;
	public function __construct($companyId) {
		$this->companyId = $companyId;
		$this->model = new Model ();
		$sql = "SELECT * FROM config WHERE company_id = '$companyId'";
		$config = $this->model->getQuery ( $sql );
		if (isset ( $config [0] )) {
			foreach ( $config as $value ) {
				$this->data [$value ['name']] = $value ['value'];
			}
		} else {
			$this->data = false;
		}
	}
	public function __set($name, $value) {
		$sql = "UPDATE config SET value = '$value' WHERE name = '$name' AND company_id = '$this->companyId'";
		$res = $this->model->getQuery ( $sql, false );
		if ($res > 0)
			$this->data[$name] = $value;
	}
	public function __get($name) {
		if ( isset($this->data[$name])) return $this->data[$name];
 	 	return false;
	}
}