<?php
/**
 * @abstract session handler instance
 * @author 
 * @namespace CORE
 */

 namespace CORE;
 
 class Session
 {
 	const S_ON = TRUE;
 	const S_OFF = FALSE;
 	public $sessionState = self::S_OFF; // session instance
 	private static $instance;
 	public $sid;
 
 	public function __construct() {}

 	/**
 	 * @method getInstance();
 	 * @return    object
 	 **/
 	public static function getInstance()
 	{
 		if ( !isset(self::$instance)) {
 			self::$instance = new self;
 		}
 		self::$instance->startSession();
 	 return self::$instance;
 	}
 	
 	/**
 	 *    (Re)starts the session.
 	 *
 	 *    @return    bool    TRUE if the session has been initialized, else FALSE.
 	 **/
 	public function startSession()
 	{
	 	 if ( $this->sessionState == self::S_OFF ){
	 			$this->sessionState = session_start();
	 			$this->sid = session_id();
	 	 }
	 	return $this->sessionState;
 	}
 	
 	
 	/**
 	 * @method __set( $name , $value ) :: set value in session
 	 * @uses $instance->hola = something;
 	 * @param string :: name :: Name of the datas.
 	 * @param anythig:: data to store
 	 **/
 	public function __set( $name , $value )
 	
 	{
 		$_SESSION[$name] = $value;
 		
 	}

 	/**
 	 * @method __get( $ name ) :: Return data from session
 	 * @uses $instance->foo;
 	 * @param string :: $name 
 	 * @return value in session
 	 **/
 	public function __get( $name ) 
 	{
 	 if ( isset($_SESSION[$name])) return $_SESSION[$name];
 	 return false;
 	}
 	
 	public function encrypt($name,$value)
 	{
 		$_SESSION[$name] = @base64_encode($value);
 			
 	}
 	
 	public function decrypt( $name )
 	{
 		if(self::__isset($name)){
 			$data = @base64_decode($_SESSION[$name]);
 			return $data[1];
 		}
 		return false;
 		
 	}
 
 	/**
 	 * __isset( $name ) :: validates if session value exist on session
 	 * @param string :: $name
 	 * @return if exits
 	 */
 	public function __isset( $name ) {
 		return isset($_SESSION[$name]);
 	}
 
 
 	public function __unset( $name ) {
 		unset( $_SESSION[$name] );
 	}
 
 
 	/**
 	 *    Destroys the current session.
 	 *    @return    bool    TRUE is session has been deleted, else FALSE.
 	 **/
 	public function destroy() {
 	
 	if ( $this->sessionState == self::S_ON ) {
 		session_destroy();
 		$this->sessionState = self::S_OFF;
 		 return true;
 	 }
	return false;
 	}
 	
 	/**
 	 * @method accessProfiles();
 	 * @param 
 	 **/
 	public static function accessProfiles($arrayprofiles = array())
 	{   $acceso = false;
 		foreach ($arrayprofiles as $key => $value){
 			if( in_array($value, $_SESSION['profiles'])){
 				
 				$acceso = true;
 			}
 			if(!$acceso){
 				 @header("Location: /administracion/acceso");die;
 			}
 			
 		}	
 		
 	}
 	
 }