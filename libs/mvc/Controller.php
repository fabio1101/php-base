<?php

namespace MVC;

use CORE\Menu;
use CORE\User;
use CORE\Session;

class Controller{

	protected $user;
	public $view;
	public $content;
	public $menu;
	
	function __construct() {
		$this->view = new View();
		$this->user = new User();
		$this->menu = new Menu ();
		$this->setup();
	}
	private function setUp() {
		$this->content ['DATE'] = @date ( 'Y' );
		$this->content ['APP_NAME'] = APP_NAME;
		$this->content ['USER_IP'] = $_SERVER ['REMOTE_ADDR'];
		$s = Session::getInstance();
		$this->content ['USERNAME'] = $s->name;
	}
	public function logged() {
		return $this->user->logged();
	}
	public function validUser() {
		$utils = new Utils();
		if ($this->logged()){
			if($this->user->authenticUser())
				return true;
			else 
				$utils->redirect('index/logout');
		}
		$utils->redirect('index/index');
	}
	public function hasPermission($perm = 'C'){
		if (!$this->user->hasPermission($perm)){
			$utils = new Utils();
			$utils->redirect('index/redir');
		}
	}
	public function login($un, $pass){
		//$this->user->logoutUser();
		return $this->user->loginUser($un,$pass);
	}
	
	public static function noty($texto, $tipo = 'success'){
		$noty = "<script>$(document).ready(function(){toastr.$tipo('$texto');});</script>";
		//$noty = "<script>$(document).ready(function(){noty({text:'<h4>$texto</h4>',type:'$tipo',layout: 'topRight'});});</script>";
		return $noty;
	}
}