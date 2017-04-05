<?php

/**
 * @author 
 * @namespace CORE
 */
namespace CORE;

/**
 * class User
 * query user segun id de usuario
 *
 * debe mostrar los banners activos
 */
use MVC\Model;
use \PDO;
use CORE\Session;
use CORE\CustomCode;
use MVC\Utils;

class User {
	private $Session = false;
	private $model = false;
	private $cookieName = 'boxesAuth';
	public function __construct() {
		$this->model = new Model ();
		$this->Session = Session::getInstance ();
	}
	
	/**
	 *
	 * @method loginUser( )
	 * @return boolean false si no existe el usuario / String :: id de usuario si existe
	 * @abstract verifica si el usuario existe o no
	 */
	public function loginUser($nick, $password) {
		if (! empty ( $nick ) && ! empty ( $password )) {
			$password = md5 ( $password );
			$SQL = "SELECT * FROM user WHERE email = '$nick'";
			$userData = $this->model->getQuery ( $SQL );
			if (isset ( $userData [0] )) {
				$userData = $userData [0];
				if ($userData ['status'] != '1') {
					return 4;
				}
				if ($userData ['password'] == $password) {
					$this->Session->startSession ();
                    $this->Session->encrypt ('id', $data[0]['id']);
                    $this->Session->name = $data [0] ['first_name'].' '.$data [0] ['last_name'];
					$this->Session->encrypt ( 'user_id', $userData ['id']);
					return 'success';
				} else {
					return 6;
				}
			} else {
				return 3; // Username doesnt exist
			}
		} else {
			return 2; // empty username or password
		}
		return 0; // unknown error
	}
	public function logoutUser() {
		$this->Session->startSession ();
		$this->Session->destroy ();
	}
	public function logged() {
		if ($this->Session->startSession ()) {
			if ($this->Session->__isset ( 'EMAIL' )) {
				return true;
			}
		}
		return false;
	}
	public function authenticUser() {
		$this->Session->startSession ();
		$user_id = $this->Session->decrypt ( 'user_id' );
		if ($user_id) {
			$SQL = "SELECT password FROM user WHERE id = '$user_id'";
			$userData = $this->model->getQuery ( $SQL );
			if (isset ( $userData [0] )) {
				if ($userData [0] ['password'] == $this->Session->decrypt ( 'PASSWD' ))
					return true;
			}
		}
		return false;
	}
	public function hasPermission($type) {
		if ($this->Session->decrypt ( 'TYPE' ) == $type || $this->Session->decrypt ( 'TYPE' ) == $type.'O')//@todo verificar esto con regex
			return true;
		return false;
	}
	function Check_Mail($email) {
		if (preg_match ( '/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,4}$/', $email )) {
			return true;
		}
		return false;
	}
	function S_Input($value) {
		if (@get_magic_quotes_gpc ())
			$value = stripslashes ( $value );
		if (! is_numeric ( $value ))
			$value = "'" . @function_exists ( 'mysql_real_escape_string' ) ? @mysql_real_escape_string ( $value ) : @mysql_escape_string ( $value ) . "'";
		return $value;
	}
}
	