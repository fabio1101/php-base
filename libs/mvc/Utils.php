<?php

namespace MVC;

use CORE\Config;
use model\GenModel;

class Utils{

	private $startTime;

	public function _construct() {
		$this->startTime = $this->microtime_float ();
	}

	public function CleanFileName($name) {
		$name = preg_replace ( "/[^a-zA-Z0-9\/_]/", '', $name );
		return ucfirst ( strtolower ( $name ) );
	}

	public function LoadFile($filePath) {
		$filePath = APP_PATH . $filePath;
		if (@file_exists ( $filePath ) && @is_file ( $filePath )) {
			require_once ($filePath);
			return true;
		} else
			throw new \Exception ( __CLASS__ . PHP_EOL . 'Error: file not found ' . $filePath );
		return false;
	}

	public function redirect($path) {
        @ob_clean();
        $has_vars = preg_match('/\?/', $path);
        if ($has_vars) {
            $data = explode('?', $path);
        } else {
            $data = array($path,'');
        }
        $address = '/' . $data[0];
        $vars = $data[1];
        $header = '<script src="/public/bx_dashboard/js/jquery.min.js"></script>'.
                  '<script src="/public/bx_js/boxes.js"></script>';
        $code = "<body><script>redirect('$address','$vars');</script></body>";
        echo $header . $code;
        die();
	}

	public function getVariable($Var, $Option = 0) {
		global $_GET, $_POST;
		switch ($Option) {
			// En caso de 0 se toma de cualquier forma el dato, GET o POST.
			case 0 :
			default :
				return isset ( $_GET [$Var] ) ? $_GET [$Var] : (isset ( $_POST [$Var] ) ? $_POST [$Var] : NULL);
				break;
			// En caso de 1 se toma solo por GET.
			case 1 :
			case 'get' :
				return isset ( $_GET [$Var] ) ? $_GET [$Var] : NULL;
				break;
			// En caso de 2 se toma solo por POST.
			case 'post' :
			case 2 :
				return isset ( $_POST [$Var] ) ? $_POST [$Var] : NULL;
				break;
		}
	}

	public function getAllPOST() {
		global $_POST;
		$retorno = null;
		foreach ( $_POST as $name => $val ) {
			$retorno [$name] = $val;
		}
		return $retorno;
	}

	public function jsonOut($data = false) {
		@header ( 'Cache-Control: no-cache, must-revalidate' );
		@header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		@header ( 'Content-type: application/json; charset=UTF-8' );
		self::utf8_encode_deep ( $data );
		echo json_encode ( $data );
		die ();
	}

	public function microtime_float() {
		list ( $usec, $sec ) = explode ( " ", microtime () );
		return (( float ) $usec + ( float ) $sec);
	}

	public function timer() {
		return '<!--Execution-Time : ' . ($this->microtime_float () - $this->startTime) . '-->';
	}

	public static function utf8_encode_deep(&$input) {
		// Commented for problem with accents.
		/*if (is_string ( $input )) {
			$input = utf8_encode ( $input );
		} else if (is_array ( $input )) {
			foreach ( $input as &$value ) {
				self::utf8_encode_deep ( $value );
			}
			
			unset ( $value );
		} else if (is_object ( $input )) {
			$vars = array_keys ( get_object_vars ( $input ) );
			
			foreach ( $vars as $var ) {
				self::utf8_encode_deep ( $input->$var );
			}
		}*/
	}

	private function notification($mensaje = '', $tipo = 'success') {
		switch ($tipo) {
			case 'info' :
			case 'warning' :
			case 'danger' :
				break;
			case 'success' :
			default :
				$tipo = 'success';
		}
		$struct = "<div class='alert alert-$tipo' role='alert'>$mensaje</div>";
		return '<script>$("#content").prepend("' . $struct . '")</script>';
	}

	public static function short($data, $lenght = 300, $ellipsis = false) {
		$text = (strlen ( $data ) > $lenght) ? substr ( $data, 0, $lenght ) : $data;
		return ($ellipsis && strlen ( $data ) > $lenght)? $text.'...' : $text;
	}

	public static function raiseBoxCode($companyId){
		$config = new Config($companyId);
		$config->BOX_CODE_NUMBER = $config->BOX_CODE_NUMBER + 1; 
	}

    public static function raiseCourierGuide($companyId){
        $config = new Config($companyId);
        $config->COURIER_GUIDE_NUMBER = $config->COURIER_GUIDE_NUMBER + 1;
    }

	public static function getBoxNumber($bxNumber){
		$bxNumber = (int)$bxNumber;
		if ($bxNumber <= 9)
			return '000000000'.$bxNumber;
		elseif ($bxNumber <= 99)
			return '00000000'.$bxNumber;
		elseif ($bxNumber <= 999)
			return '0000000'.$bxNumber;
		elseif ($bxNumber <= 9999)
			return '000000'.$bxNumber;
		elseif ($bxNumber <= 99999)
			return '00000'.$bxNumber;
		elseif ($bxNumber <= 999999)
			return '0000'.$bxNumber;
		elseif ($bxNumber <= 9999999)
			return '000'.$bxNumber;
		elseif ($bxNumber <= 99999999)
			return '00'.$bxNumber;
		elseif ($bxNumber <= 999999999)
			return '0'.$bxNumber;
		elseif ($bxNumber <= 9999999999)
			return $bxNumber;
	}

    public function getCourierGuideNumber($number, $char_number = 8){
        $number = (String)$number;
        $count = strlen($number);
        for ($i = $count; $i < $char_number; $i++) {
            $number = '0' . $number;
        }
        return date('y') . date('m') . $number;
    }

	public function validateScript() {
		return '<script>$.validate({  
					language	 		: myLanguage,
					form				: \'#main-form\',
					validateOnBlur		: false,
					errorMessagePosition: \'top\' ,
					scrollToTopOnError	: true,
					onError :	function() { return false; },
					onSuccess : function() { return true; },
				});</script>';
	}

	public function loginMsg($msg_code){
		switch ($msg_code){
			case 2:
				return array( "TYPE" => 'bg-danger',
					"ERROR_MSG" => 'xxx');
			default:{
				return array( "TYPE" => 'bg-danger',
					"ERROR_MSG" => 'Error no identificado, consulte su administrador');
			}
		}
	}

	public static function _audit($userid, $usertype, $message){
		$model = new GenModel();
		$model->audit($userid, $usertype, $message);
	}

    public function cleanPhoneNumber($to_clean){
        return preg_replace("/[^0-9]/", '', $to_clean);
    }

    public function formatPhoneNumber($to_format){
        $digits = strlen($to_format);
        if ($digits <= 10 && $digits > 7){
            $formatted_phone = 
                "(".
                substr($to_format, 0, $digits-7).
                ") ".
                substr($to_format, $digits-7, 3).
                "-".
                substr($to_format, $digits-4, 4);
        } elseif($digits == 7){
            $formatted_phone = 
                substr($to_format, 0, 3).
                "-".
                substr($to_format, 3, 4);
        } elseif($digits > 10) {
            $formatted_phone = 
                "+".
                substr($to_format, 0, $digits-10).
                " (".
                substr($to_format, $digits-10, 3).
                ") ".
                substr($to_format, $digits-7, 3).
                "-".
                substr($to_format, $digits-4, 4);
        } else {
            $formatted_phone = $to_format;
        }
        return $formatted_phone;
    }
}