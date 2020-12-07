<?php

/**
 * 
 * @author 
 * @abstract MVC\Bootstrap
 *
 */
namespace MVC;

class Bootstrap {
	private $_controllerDefault = 'index';
	public $_config;
	private $_urlController; // nombre del controlador
	private $_urlMethod; // nombre del metodo o null si no existe
	private $_urlValue = array (); // array que contiene valores de variables
	private $_pathModel; // ruta del directorio model
	private $_pathView; // ruta del directorio view
	private $_pathController; // ruta del directorio controller
	public $url; // variable para obtener la URL
	public $urlSegments; // contiene la url con explode
	public $urlSlashPath;
	public function __construct() {
		if (isset ( $_GET ['url'] )) {
			$url = rtrim ( $_GET ['url'], '/' );
			$url = filter_var ( $url, FILTER_SANITIZE_URL );
			$this->url = $url;
		} else
			$this->url = '';
	}
	
	/**
	 *
	 * @method setPathRoot($path)
	 * @param
	 *        	string :: $path :: directorio actual del aplicativo
	 * @abstract ubicacion de las rutas del model, del controller y de view
	 */
	public function setPathRoot($path) {
		$this->_pathRoot = rtrim ( $path, '/' ) . '/';
		$this->_pathController = $this->_pathRoot . APP_CONTROLLERS . '/';
		$this->_pathModel = $this->_pathRoot . APP_MODELS . '/';
		$this->_pathView = $this->_pathRoot . APP_VIEWS . '/';
	}
	
	/**
	 *
	 * @method setControllerDefault($controller)
	 * @param
	 *        	string :: $controller :: default controller
	 * @abstract setup and load default controller if nothing comes trough url
	 */
	public function setControllerDefault($controller) {
		$this->_controllerDefault = strtolower ( $controller );
	}
	
	/**
	 *
	 * @method init($overrideurl = false) :: Init Boostrap Application
	 * @param
	 *        	boolean :: $overrideurl :: overrideurl
	 * @todo unknow action of $overridelurl
	 */
	public function init($overrideurl = false) {
		if (! isset ( $this->_pathRoot ))
			die ( 'Error : Application path root undefined' );
		
		$urlToBuild = (($overrideurl == true) ? $overrideurl : $this->url);
		$this->_buildComponents ( $urlToBuild );
		$this->_initController ();
	}
	
	/**
	 *
	 * @method _buildComponents( $url )
	 * @param
	 *        	string :: $url :: la peticion url que llega al applicativo
	 * @abstract evalua la url que llega definiendo controlador / methodo / variables si existe
	 * @final construye define variables con el controlador, metodos y variables si existen
	 */
	private function _buildComponents($url) {
		$url = @explode ( '/', $url );
		$this->urlSegments = $url;
		
		// $this->_initurlSlashPath($url);// es contatenar ../ n veces
		$this->_urlController = ucwords ( $url [0] );
		$this->_urlMethod = ((isset ( $url [1] )) ? strtolower ( $url [1] ) : 'Index');
		$this->_urlValue = @array_splice ( $url, 2 );
		
		if (! isset ( $this->_urlController ) || empty ( $this->_urlController )) {
			
			$this->_urlController = $this->_controllerDefault;
			$this->_urlMethod = 'Index';
			$this->_urlValue = null;
		}
	}
	
	/*
	 * Configura la longitud de la ruta con punto punto y barra inclinada
	 * esto debe estudiarse si existe es quizas algo requerido ./ urls relativas ???
	 * private function _initurlSlashPath() {
	 *
	 * $this->urlSlashPath = '';
	 *
	 * $realSegments = explode('/', $this->url);
	 * for ($i = 1; $i < count($realSegments); $i++) {
	 * $this->urlSlashPath .= '../';
	 * }
	 * }
	 */
	
	/**
	 *
	 * @method _initController() :: Init Controller requested or default
	 * @throws \Exception
	 */
	private function _initController() {
		$this->_urlController = $this->CleanFileName ( $this->_urlController );
		$this->_urlMethod = $this->CleanFileName ( $this->_urlMethod ) . APP_METHODS;
		
		$ToLoad = $this->_pathController . $this->_urlController . '.php';
		
		if ($this->LoadFile ( $ToLoad )) {

			$controller = $this->_urlController;
			$this->controller = new $controller (  );
			//$this->controller->_Configuration = $this->_config;
			//$this->controller->pathModel = $this->_pathModel;
		} else
			throw new \Exception ( __CLASS__ . PHP_EOL . 'Unabled to load Controller' );
			
			/*
		 * verifica la existencia del metodo
		 */
		
		if (isset ( $this->_urlMethod ) && method_exists ( $this->controller, ( string ) $this->_urlMethod )) {
			
			/*
			 * verifica la existencia de variables para realizar llamado a method ( args )
			 */
			
			if (! empty ( $this->_urlValue )) {
				
				switch (count ( $this->_urlValue )) {
					case 1 :
						$this->controller->{$this->_urlMethod} ( $this->_urlValue [0] );
						break;
					
					case 2 :
						$this->controller->{$this->_urlMethod} ( $this->_urlValue [0], $this->_urlValue [1] );
						break;
					
					case 3 :
						$this->controller->{$this->_urlMethod} ( $this->_urlValue [0], $this->_urlValue [1], $this->_urlValue [2] );
						break;
					
					case 4 :
						$this->controller->{$this->_urlMethod} ( $this->_urlValue [0], $this->_urlValue [1], $this->_urlValue [2], $this->_urlValue [3] );
						break;
					
					case 5 :
						$this->controller->{$this->_urlMethod} ( $this->_urlValue [0], $this->_urlValue [1], $this->_urlValue [2], $this->_urlValue [3], $this->_urlValue [4] );
						break;
				} // switch
			} else
				$this->controller->{$this->_urlMethod} ();
		} else {
			throw new \Exception ( __CLASS__ . ' :: Application Error : Method does not exists ' . $this->_urlMethod );
		}
	}
	
	/**
	 *
	 * @method CleanFileName($name) :: Sanitage Filename to include
	 * @param
	 *        	string :: $name :: FileName to sanitage
	 * @return string :: clean string
	 */
	private function CleanFileName($name) {
		$name = preg_replace ( "/[^a-zA-Z0-9\/_]/", '', $name );
		return ucfirst ( strtolower ( $name ) );
	}
	
	/**
	 *
	 * @method LoadFile( $filePath ) :: require_once specific file
	 * @param
	 *        	string :: $filePath :: filepath to include
	 * @throws \Exception
	 */
	private function LoadFile($filePath) {
		if (@file_exists ( $filePath ) && @is_file ( $filePath )) {

			require_once ($filePath);
			return true;
		} else {

            throw new \Exception ( __CLASS__. ' => ' . 'Error: file not found ' . $filePath . ' for URL (' . $this->url . ')');
			return false;
		}
	}
}