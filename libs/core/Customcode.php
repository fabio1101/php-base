<?php
/**
 * Customcode
 * @author 
 * @version 1.0 Beta
 * @method __construct()
 * @todo 
 **/

 namespace CORE;

 use MVC\Utils;
	class CustomCode  {
    // public Vars
    public	$code;														// the code file
    public  $ocode;														// original code
    private $config = array('DEFAULT_PATH' 		=> 'public/templates');	// templates path
    private $separator;
	
	/**
	 * __constructor
	 * @method __contruct( $file or content, loadfile = true )
	 * @param varchar $file		:: tpl file or content to process
	 * @param int	  $loadfile :: true => load html file / false => content is ont $file argument
	 */
	public function __construct($file,$loadfile = true)
	{
	 if($loadfile) {
	   	   $this->separator =  ( (strtoupper(substr(PHP_OS,0,3)) == 'WIN')? '\\' : '/' ); // win/*nix ??
	       $file = $this->config['DEFAULT_PATH'] . $this->separator .$file;      
	       $file = $file.'.html';
	      
	    if(file_exists( $file) && is_file( $file ) ) {	    	
	        $fp = fopen( $file ,'r');	        
	        $this->code['ALL'] = $this->encode( fread($fp, filesize($file) )  );	        
	        @fclose($fp);    
	   } else
	       	$this->code['ALL'] = '<h1>*************** File tpl not found '.$file.'********************</h1>';	       
	   			   		
	  } else 
	  		$this->code['ALL'] = $this->encode( $file );  
	  		
	 $this->ocode = $this->code;
	}
	
	/**
	 * @method
	 * @param unknown_type $varName
	 * @param unknown_type $varVals
	 * @param unknown_type $partName
	 */
	public function assign($varName,$varVals,$partName = 'ALL')
	{
		Utils::utf8_encode_deep($varVals);
	    if( is_array($varVals) ) {       
	        foreach($varVals as $key => $value)
	        	$this->code[ $partName ] = preg_replace("({{$varName}" . '.' . "{$key}})" , ($value), $this->code[ $partName ]);
	        	//$this->code[ $partName ] = preg_replace("({{$varName}" . '.' . "{$key}})" , $this->encode($value), $this->code[ $partName ]);
	           
	    }else 
	        $this->code[ $partName ] = preg_replace("({{$varName}})", $varVals, $this->code[ $partName ]);
	    		
	}
	
	public function bassign($varName,$varVals,$partName = 'ALL', $cut = false)
	{
		Utils::utf8_encode_deep($varVals);
		if( is_array($varVals) ) {
		    $this->code[ $partName ] = '';
		    foreach($varVals as $myarray)
		    {
		        $this->code[ $partName ] .= $this->ocode[ $partName ];
		        foreach($myarray as $key => $value)
				{
					//$this->code[ $partName ] = preg_replace("({{$varName}" . '.' . "{$key}})" , $this->encode( ($cut)? ($this->cut($value)) : $value ), $this->code[ $partName ]);
					$this->code[ $partName ] = preg_replace("({{$varName}" . '.' . "{$key}})" , ( ($cut)? ($this->cut($value)) : $value ), $this->code[ $partName ]);
				}
				
		    }
		}
	}
		
	public function encode($value, $codificacion = 'UTF-8')
	{
	    /*$enc = mb_detect_encoding( $value ,'UTF-8, ISO-8859-1');
	    return iconv($enc, $codificacion, $value ) ;	    */
	    return $value;
	}
	
	
	public function part($arrayNames = 'ALL')
	{
	    if( is_array($arrayNames) ) {
	        $parts = @explode("{PART}",$this->code['ALL']);
	        foreach($arrayNames as $key => $value)
	            $this->code[ $value ]  = $parts[ $key ];
	    }
	 $this->ocode  = $this->code; 
	}
	
	public function inject($toInject, $partName)
	{
	  $this->code[ $partName ] = preg_replace("({_{$toInject}_})" , $this->code[ $toInject ] , $this->code[ $partName ]);
	}
	
	public function printOut( $part = 'ALL')
	{   
	 if( key_exists( $part, $this->code) )
		return $this->code[ $part ];
	 return '<h1>*************** KEY in Body not Found ************</h1>';
	}
	

	/**
	 * @method set_config(($key, $value) :: to modify default configs
	 * @param var $key of config
	 * @param var $value of config param
	 * return true on set overwhise returns false
	 */
	public function set_config($key, $value)
	{
		if (isset($this->config[$key])) {
			$this->config[$key] = $value;
			return true;
		}
	 return false;
	}
	
	public function cut($text, $size = 100)
	{
		if (strlen($text) >= $size){
			return substr($text, 0, $size-3).'...';
		}
		return $text;
	}

}
?>