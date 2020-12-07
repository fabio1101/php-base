<?php
/**
 * Handleupload
 * @author Talento Humano
 * @version 1.0 Beta
 * @method __construct()
 * @todo IMPORTANT FIX FILENAME LONG AND CHARSET OF RECORD IT MUST BE CLOSE TO UTF FORMAL
 **/
namespace CORE;

use MVC\Utils;
class Handleupload
{

    // public Vars
    public $file;											// the file
    public $file_info;    									// file_info submited file info
    public $file_name;    									// file_name end filename conversion
    public $save_path;    									// save_path full destination
    public $separator;										// to define separator win/lin
    public $session;										// to take global storage param




    // Private Vars
    private $_error_message = null;							// error display var

	private $config = array('MAX_UPLOAD_SIZE'   => 0, 		// 0 UNLIMITED
							'ALLOWED_EXTS' 		=> array('txt','png','doc','xls','pdf','jpg','bmp','csv','rtf','gif','docx','xlsx','ppt','jpeg','tif','tiff'),
															// extensions allowed
							'DEFAULT_PATH' 		=> '/uploads/photos',	// default save path
							'DEFAULT_CHMOD'     => 0755); // file chmod on server

	/**
	 * __constructor
	 */
	public function __construct(){
	    $this->separator =  ( (strtoupper(substr(PHP_OS,0,3)) == 'WIN')? "\\" : "/" ); // win/*nix ??
	    $this->set_config('MAX_UPLOAD_SIZE', 6000000); // 6 Mb Aprox
   	}

	public function set_config($key, $value)
	{		
		if (isset($this->config[$key])) {
			$this->config[$key] = $value;
			return true;
		}
	 return false;
	}
	
	public function config(){
		return $this->config;
	}

	/**
	 * @method file_status($filename,$path = '')
	 * @param varch = $filename		:: filename to check
	 * @param varch = $path			:: path to file
	 *
	 * @return screen status of file (icon etc) |if exists
	 */
	public function file_status($filename,$type = '')
	{
		$filename = strtolower($filename);
	    // cause it always is on public ?
	    $icon_path = $this->separator.'public'.$this->separator.'img'.$this->separator.'icons'.$this->separator;
	    //@chdir('../');
	    $mensaje = false;
	    $filetocheck  = APP_PATH.$this->separator.$this->config['DEFAULT_PATH']. $this->separator.$filename;
	    //@chdir($realpath);

	    if( @is_file($filetocheck) && @file_exists($filetocheck)){
	        $d_id = (int) rand(0,1000);
	        $partes_archivo = pathinfo($filetocheck);
	        $mensaje .= '<img src="' . $icon_path . $partes_archivo['extension']. '.png" width="64" height="64" hspace="2" vspace="3" border="0" align="left" style="margin-right:5px;"/>';
	        $mensaje .= '<div style="float:left;">Archivo : <b>' . $filename . '</b><br>';
	        $mensaje .= 'Tama&ntilde;o de archivo : <b>'.round( @filesize($filetocheck) / 1048576, 2) . ' Mbs</b><br>';
	        // Desactivado el tipo de archivo en la siguiente linea, problemas con la funcion mime_content_type (Depreciada) y se probo con fileinfo y no se logro organizar
	        $mensaje .= 'Ultima actualizaci&oacute;n : <b>'. @date("F d Y H:i:s.", @filemtime($filetocheck)) . '</b> Tipo de archivo : <b>'.$this->replacemimetype($partes_archivo['extension']) .'</b><br></div>';
	        $mensaje .= '<a id="download_'. $d_id .'" style="float:left;margin:2em" class="btn btn-default" target="_blank" href="/gen/download?doctype='.$type.'&name='. $filename.'">Descargar archivo</a>';
	     return '<div style="border: #DEDEDE 1px solid;padding:4px;height:80px;">' . $mensaje .'</div>';
	    }else {
	        $mensaje .= '<img src="' . $icon_path .'error.png" width="64" height="64" hspace="3" vspace="3" border="0" align="left" />';
	        $mensaje .= 'Advertencia archivo : <b>' . $filename . '</b><br>';
	        $mensaje .= '<b style="color:red">No ha sido hallado en la biblioteca de documentos, por favor verifique con la administraci&oacute;n sobre el estado del archivo</b>';
	        return '<div style="border: #DEDEDE 1px solid;padding:4px;height:70px;">' . $mensaje .'</div>';
	    }
	  return false;
	}
	
	//'','png','','','','jpg','bmp','','','gif','','','ppt'),
	public function replacemimetype($ext){
		switch ($ext){
			case 'doc':
			case 'xls':
			case 'pdf':
			case 'rtf':
			case 'docx':
			case 'txt':{
				return 'Documento de texto';
				break;
			}
			case 'xlsx':
			case 'xls':
			case 'csv':{
				return 'Hoja de c&aacute;lculo';
				break;
			}
			case 'ppt':{
				return 'Presentaci&oacute;n de PowerPoint';
				break;
			}
			case 'jpg':
			case 'jpeg':
			case 'bmp':
			case 'gif':
			case 'png':{
				return 'Archivo de imagen';
				break;
			}
			default:{
				return 'Extensi&oacute;n no reconocida.';
				
			}
		}
	}
	
	public function Getpath($relative = 'none')
	{
		$realpath = APP_PATH;
		@chdir('../');
		$paths = pathinfo($relative);
		$filetocheck  = APP_PATH . $this->separator.$this->config['DEFAULT_PATH'] . $this->separator.rtrim($paths['dirname'], $this->separator) . $this->separator. $paths['basename'];
		@chdir($realpath);
		if( @is_file($filetocheck) && @file_exists($filetocheck)){
			return $filetocheck;
		}
      return 'filenotfound';
	}

	public function file_image($myfile,$size = 64,$topath = '')
	{

		$this->set_config('DEFAULT_PATH', 'public');
		$icon_path = $this->separator.$topath.$this->separator;
		$realpath = APP_PATH;
		@chdir('../');
		$mensaje = false;
		$filetocheck  = APP_PATH . $this->separator . $this->config['DEFAULT_PATH'] . $this->separator . rtrim($topath, $this->separator) . $this->separator . $myfile;
		
		@chdir($realpath);
		$d_id = (int) rand(0,1000);

		if( @is_file($filetocheck) && @file_exists($filetocheck)){
			$partes_archivo = pathinfo($filetocheck);
			
			/***
			 * @TODO SOLVE FUCKING PROBLEM WITH WINDOWS
			 * $mensaje .='<div id="pic_'.$d_id.'" class="ui-widget-content ui-corner-all" style="padding:2px;"><img src="/download/image/?img='. $topath . '/'. $filename . '&s='.$size.'" width="'.$size.'" height="'.$size.'" hspace="0" vspace="0" style="border:#CCCCCC 1px solid;" class="ui-corner-all" align="absmiddle"/></div>';
			 */

			$mensaje .='<div id="pic_'.$d_id.'" class="ui-widget-content ui-corner-all" style="padding:2px;"><img src="/Download/image/?img='. $topath . '/'. $myfile . '&maxw='.$size.'" width="'.$size.'"  hspace="0" vspace="0" style="border:#CCCCCC 1px solid;" class="ui-corner-all" align="absmiddle"/></div>';
		}else
			
			$mensaje .='<div id="pic_'.$d_id.'" class="ui-widget-content ui-corner-all" style="padding:2px;"><img src="' . $icon_path . 'personal.jpg" width="'.(int)$size.'"  hspace="0" vspace="0" style="border:#CCCCCC 1px solid;" class="ui-corner-all" align="absmiddle"/></div>';
	 return $mensaje;
	}

	public function Download($myfile)
	{
	    $realpath = APP_PATH;
	    @chdir('../');
	    $deliverfile  =  APP_PATH. $this->separator. $this->config['DEFAULT_PATH'] . $this->separator . $myfile;
	    @chdir($realpath);

	    if( @file_exists($deliverfile) && is_file($deliverfile) ){

		    header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Cache-Control: private",false);
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=\"" .$myfile ."\";");
		    header("Content-Transfer-Encoding: binary");
		    header("Content-Length: ".@filesize($deliverfile) );

		    @ob_clean();
		    @flush();
		    @readfile($deliverfile);
		    exit;

	    }else {
	        return 'Archivo no encontrado';
	    }
	}

	/**
	 * @method save_file($file, $topath) :: process file upload
	 * @param keyname of $_FILE[ NAME ] = $file
	 * @param var location 				= $topath
	 */
	public function save_file($file)
	{
		$this->file            = strtolower($file);
		
		$this->save_path       = APP_PATH . $this->separator.$this->config['DEFAULT_PATH'].$this->separator;
		
		try {
			//$this->check_folder(); 		// check destination folder
			$this->check_file();		// check file uploaded
			$this->move_file();			// move file uploaded
			$this->chmod_file();		// chmod file
			return true;
		} catch (\Exception $e) {
			if ($e->getCode() == 0)
			{	
				$this->error_message = $e->getMessage();		
				die($this->error_message);		
				return false;
			} else {
				trigger_error($e->getMessage(), E_USER_ERROR);
				$this->error_message = 'ocurrio un error, por favor intente de nuevo.';
				return false;
			}
		}

	}

	/**
	 * chmod_file :: set correctly chmod for uploaded files
	 * @throws \Exception
	 */
	private function chmod_file()
	{
	   	if (!chmod($this->save_path.$this->separator.$this->file_name, $this->config['DEFAULT_CHMOD'])) {
			throw new \Exception("fallo al cambiar de permisos para el archivo",0);
		}
	}

	/**
	 * move_file() :: set the temp uploaded on correct store location
	 * @throws \Exception
	 */
	private function move_file()
	{
		$old = error_reporting(0);
		//print_r($this->save_path.$this->file_name);die;
		if (!move_uploaded_file($_FILES[$this->file]['tmp_name'], $this->save_path.$this->file_name)) {
			throw new \Exception("Error moviendo el archivo a la carpeta de destino",0);
		}
	 	error_reporting($old);
	}

	/**
	 * @method check_folder :: check destination folder, it must exists and writable to user (apache,etc)
	 * @throws \Exception
	 */
	private function check_folder()
	{
		if (!is_dir($this->save_path)) {
			throw new \Exception("Carpeta de destino '{$this->save_path}' no existe o es inaccesible.",0);
		}
		if (!is_writable($this->save_path)) {
			throw new \Exception("Carpeta de destino '{$this->save_path}' sin permisos de escritura",0);
		}
	}

	/**
	 * @method set_file_info() :: check filename adds timestamp to filename
	 * @todo :: prevent filename with latin chars and dual namespaces
	 */
	private function set_file_info()
	{
		
		$this->file_info = pathinfo($_FILES[$this->file]['name']);
		((isset($this->file_info['extension']))? $this->file_info['extension']: $this->file_info['extension'] = 'null');
		$this->file_name = Utils::short($this->file_info['filename'],10).'_'.date('Ymdhis'). ".{$this->file_info['extension']}";//@date(Ymd_his)
		//$this->file_name = microtime(true). ".{$this->file_info['extension']}";//@date(Ymd_his)
		//$this->save_path = $this->destination . $this->file_name;
	}
	
	public function set_file_name($name)
	{

		//print_r($this->save_path.'/'.$this->file_name);DIE;
		$old = error_reporting(0);
		if (!move_uploaded_file($_FILES[$this->file]['tmp_name'], $this->save_path.'/'.$name)) {
			throw new \Exception("error moviendo el archivo a la carpeta de destino",0);
		}
	 	error_reporting($old);
	}
	
	/** Se comentaron los siguientes metodos debido a que se encuentran repetidos dentro de la clase

	/**
	 * @method check_folder :: check destination folder, it must exists and writable to user (apache,etc)
	 * @throws \Exception
	 */
	/*private function check_folder()
	{
		if (!is_dir($this->save_path)) {
			throw new \Exception("Carpeta de destino '{$this->save_path}' no existe o es inaccesible.",0);
		}
		if (!is_writable($this->save_path)) {
			throw new \Exception("Carpeta de destino '{$this->save_path}' sin permisos de escritura",0);
		}
	}*/

	/**
	 * @method set_file_info() :: check filename adds timestamp to filename
	 * @todo :: prevent filename with latin chars and dual namespaces
	 */
	/*private function set_file_info()
	{
		$this->file_info = pathinfo($_FILES[$this->file]['name']);
		$this->file_name = @date("dmY_his_") . $this->file_info['filename'] . ".{$this->file_info['extension']}";
		//$this->save_path = $this->destination . $this->file_name;
	}*/


	/**
	 * @method check_file() :: verify upload behaviour
	 * @throws \Exception
	 */
	private function check_file()
	{

		// is there a file where we are looking for it
		//print_r($_FILES[$this->file]);die;
		if (!isset($_FILES[$this->file])) {
			throw new \Exception("\$_FILES['{$this->file}'] no ingresado", 0);
		}

		$this->set_file_info();
		// errores de carga
		if ($_FILES[$this->file]['error'] != 0)	{
			
		    switch ($_FILES[$this->file]['error'])	{

				case 1:
				    	throw new \Exception('Tama&ntilde;o de archivo supera el l&iacute;mite m&aacute;ximo de carga; ' . number_format(ini_get('upload_max_filesize')) . ' bytes.',0);
				    	break;

				case 2:
						throw new \Exception('Tama&ntildeo de archivo supera el l&iacute;mite de carga',0);
						break;

				case 3:
						throw new \Exception('Archivo no ha sido cargado correctamente.',0);
						break;

				case 4:
				    	throw new \Exception('No se recibio ningun archivo a cargar.',0);
				    	break;

				case 6:
				    	throw new \Exception('Carpeta de carga temporal no existe o presenta un fallo.',0);
				    	break;

				case 7:
				    	throw new \Exception('espacio de disco insufiente o fallo de escritura en disco',0);
				    	break;

				case 8:
				    	throw new \Exception("extension de archivo {$this->file_info['extension']} no aceptada.",0);
				    	break;

				default:
				    	throw new \Exception('error desconocido.',0);
				    	break;
			}

		}

		// verificar tamano de archivo
		if ($this->config['MAX_UPLOAD_SIZE'] > 0 &&
			($_FILES[$this->file]['size'] > $this->config['MAX_UPLOAD_SIZE']) )	{
		    throw new \Exception('archivo a carga no debe superar ' . number_format($_FILES[$this->file]['size']) . ' bytes.',0);
		}
		// extension de archivo aceptada
		if ( !in_array(strtolower($this->file_info['extension']), $this->config['ALLOWED_EXTS']) ) {
			throw new \Exception("extension de archivo : {$this->file_info['extension']} no es aceptada.",0);
		}

	}

}
?>