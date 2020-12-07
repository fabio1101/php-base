<?php

namespace MVC;

use CORE\Session;
use MVC\Utils as Utils;

class BaseClass extends Utils{

	public $session;
	protected $model;
	
	function __construct() {
		parent::_construct();
		$this->session = Session::getInstance();
	}
} 
