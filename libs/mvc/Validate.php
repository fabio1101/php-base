<?php
/**
 * Validacion de Datos
 * @author Modulo.historiale clinicos
 * @version 1.0
 */

namespace MVC;

class Validate {
	
	//public $status;

	/**
	 * Evalua si value es un entero
	 * @param $value
	 */
	static public function as_int($value){			
		if($value === 0)
			return true;
		return filter_var($value, FILTER_VALIDATE_INT);		
	}
	
	/** 
	 * Evalua si value es un flotante
	 * @param $value
	 */
	static public function as_float($value){	
		return filter_var($value, FILTER_VALIDATE_FLOAT);		
	}
	
	/**
	 * Evalua si value es un valor numerico
	 * @param $value
	 */
	static public function as_numeric($value){
		if(self::as_int($value) || self::as_float($value)) 		
			return true;
		return false;		
	}
	
	/**
	 * Evalua si value es un Booleano
	 * @param $value
	 */
	static public function as_bool($value){
		if ($value === false)
			return true;
		$value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		return  is_bool($value);		 	
	}
	
	/**
	 * Evalua si value es un email
	 * @param $value
	 */
	static public function as_email($value){		
		return filter_var($value, FILTER_VALIDATE_EMAIL);		
	}
	
	/**
	 * Evalua si value es un cadena de carracteres
	 * @param $value
	 * @todo incluir sanitaje SQL
	 */
	static public function as_string($value){
		if ($value == '') {
			return false;
		}
		return is_string($value);		
	}
	
	/**
	 * Evalua si value es una fecha u hora valida
	 * @param $value
	 */
	static public function as_time($value){
		$value = date_parse($value);				
		if ($value['error_count'] == 0){
			return true;}
		return false;
	}
		
	/**
	 * Evalua si el value es del tipo indicado
	 * @param $value
	 * 	@param $type : tipo de dato integer, float, boolean, etc ...
	 * @param $required
	 * @example Validate::field($data,'smallint', true)
	 */
	static public function field($value, $type, $required = false){		
				
		switch ($type){
			case 'smallint':
			case 'integer':									
				$status = self::as_int($value);
				break;
			case 'float':
			case 'real':
				$status = self::as_float($value);
				break;
			case 'email':
				$status = self::as_email($value);
				break;
			case 'boolean':
				$status = self::as_bool($value);
				break;
			case 'bigint':
			case 'numeric':
				$status = self::as_numeric($value);
				break;
			case 'date':			
			case 'time without time zone':				
				$status = self::as_time($value);
				break;	
			case 'character varying':
			case 'character':
			case 'text':
			case 'USER-DEFINED' :
				$status = self::as_string($value);
				break;			
			default:
				$status = false;
				break;
		}
		
		if($status)
			return  true;
		if((($required == false) || ($required == 'YES')) && !($status)){
			if (($value == '') || ($value == 'tonull') || ($value == null)){		
				return true;
			} 
		}
			
		//corregir validacion de requerido
		return false;
	}
		
	/**
	 * Valida si una estructura, si todos sus datos son del tipo validos
	 * @param $Object : es una instancia de un objeto de la clase Base
	 */
	static public function structure($Object , $debug = false){
		
		//print_r($Object);
		$structure 	= $Object->structure;		
		$data 		= $Object->data;
		$status = true;
		for($i = 0 ; $i < count($structure) ; $i++){
			
			if (($structure[$i]['column_name'] != $Object->pk))				
				$status = self::field($data[$structure[$i]['column_name']], $structure[$i]['data_type'] , ($data[$Object->pk] != null) ? false : $structure[$i]['is_nullable']);
						
			if($debug == true){
				echo '** Nombre: '.$structure[$i]['column_name'];
				echo ' - Tipo : '.$structure[$i]['data_type'];			
				echo ' - Valor : '.$data[$structure[$i]['column_name']].'**';
				echo (($status) ? '<br>' : '</br>'.$structure[$i]['column_name'].'-'.$data[$structure[$i]['column_name']].": valor invalido");
			}	
			
			if (!$status) break;		
		}
	return $status;
	}
	
	
}