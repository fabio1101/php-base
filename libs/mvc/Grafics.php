<?php
namespace MVC;

use CORE\CustomCode;
use MVC\Controller;
use MVC\Validate;

class Grafics
{
	public $data;
	public $opciones;
	public $custom;
	
	private $varName;
	private $varTitle;
	private $varScript;
	private $varUrl;
	private $varWidth;
	private $varHeight;
	private $varShowMarker = "false";
	private $varAxes;
	private $varRenderer;
	private $varData;
	private $varColors = "true";
	private $varAling = "center";	
	
	private $varExtras;
	
	function __construct() {		
		$this->custom = new CustomCode('graficos');
		$this->custom->part( array('HEADERS' , 'MAIN' , 'OPTIONS' , 'GRAFICAR' ) );		
	}
	
	/**
	 * @method config()	::	Inicializa variables publicas para layout
	 * @uses 			::	Could load menu from residen data
	 */
	public function config(){	
		$this->custom->inject('OPTIONS', 'MAIN');		
		$this->custom->inject('GRAFICAR', 'MAIN');		
		$this->content['MAIN'] 		= $this->custom->printOut('MAIN');
	   	return $this->content['MAIN'];
	}
	
	/**
	 * 
	 * @method prepare() 	::Asigna todos los valores al custom, para que pueda ser renderizado correctamente
	 * @return bool 		:: retorna verdadero si la preparación fue exitosa
	 */
	public function prepare(){
		$flag = false;
		try {
			$this->custom->assign('J', array("PRENDERER" =>$this->varScript ), 'MAIN');
			//Valores del Div		
			$this->custom->assign('J', array("NAMEDIV" =>$this->varName ), 'MAIN');
			$this->custom->assign('J', array("width" =>$this->varWidth ), 'MAIN');
			$this->custom->assign('J', array("height" =>$this->varHeight ), 'MAIN');
			$this->custom->assign('J', array("aling" =>$this->varAling ), 'MAIN');
			//Datos de plotear
			$this->custom->assign('J', array("URL" =>$this->varUrl ), 'MAIN');
			//Opciones de la grafica
			$this->custom->assign('J', array("SMARKER" =>$this->varShowMarker ), 'OPTIONS');			
			$this->custom->assign('J', array("TITLE" =>$this->varTitle ), 'OPTIONS');
			$this->custom->assign('J', array("RENDERER" =>$this->varRenderer ), 'OPTIONS');
			$this->custom->assign('J', array("colors" =>$this->varColors ), 'OPTIONS');			
			$this->custom->assign('J', array("AXES" =>$this->varAxes ), 'OPTIONS');			
			$this->custom->assign('J', array("EXTRAS" =>$this->varExtras ), 'OPTIONS');
			//parametros Jplot
			$this->custom->assign('J', array("NAMEDIV" =>$this->varName ), 'GRAFICAR');
			$this->custom->assign('J', array("datos" =>$this->varData ), 'GRAFICAR');
			$flag = true;			
		} catch (Exception $e) {			
			displayErrorPage($e->getMessage());			
		}				
		$this->allDefault();
		return $flag;
	}
	
	/**
	 * 
	 *@method allDefault() :: Regresa los atributos de la clase a sus valores por defencto
	 */
	public function allDefault(){
			$this->varName = '';
			$this->varTitle = '';
			$this->varScript = '';			
			$this->varWidth = 400;
			$this->varHeight = 400;
			$this->varRenderer = '';
			$this->varAling = "center";
			$this->varExtras = '';
			$this->varShowMarker = "false";
	}
	
	/**
	 * 
	 * @method getHeaders() :: Reotorna los scripts JQPLOT basicos para que se inserten en la cabecera
	 */
	public function getHeaders(){
		return $this->custom->printOut("HEADERS");
	}
		
	/**
	 * 
	 * @method title() Coloca directamente el titulo en las opciones
	 * @param string
	 */
	public function title($title){
		if(!Validate::as_string($title))
			die($title." no es un valor valido");
		$this->varTitle = $title;		
	}
	
	public function colors($colors = "false"){
		if(!Validate::as_bool($colors))
			die($colors." no es un valor valido");
		$this->varColors = $colors;
	}
	
	/**
	 * 
	 * @method name() 		::Coloca directamente el nombre de la clase
	 * @param string $name 	:: si es nulo pone un numero al random entre 0 y 1000
	 */
	public function name($name){	
		$name = !($name === null) ? $name :mt_rand(0,1000);
		if(!Validate::as_string("$name"))
			die($name." no es un valor valido");
		$this->varName = $name;		
	}
	
	/**
	 * 
	 * @method script()
	 * @param mixed 	:: El string con el nombre del plugin o el DEFINE que contiene dicho string
	 * @example setScript(BARRAS_PLUGINS)
	 * @example setScript('jqplot.barRenderer.min.js')
	 */
	public function script($script){
		$this->varScript = $script;		
	}
	
	/**
	 * 
	 * @method url()	:: Asigna al custom el URL(Action) que se va a ejecutar, este debe retornar un formato correcto JSON
	 * @param mixed 	:: action que me retorna en el formato adecuado
	 */
	public function url($url, $flag = false){		
		$this->varUrl = (!$flag) ? "data = jsonData(".$url.");" : "data =".$url.";";		
	}
	
	/**
	 * 
	 * @method width() 			::Coloca el ancho del div contenedor del grafico directamente 
	 * @param integer $width 	:: si null su valor por defecto es 100
	 */
	public function width($width){			
		$width = !($width === null) ? "$width" : "100";
		if(!Validate::as_int($width))
			die($width." no es un valor valido");
		$this->varWidth = $width;		
	}
	
	/**
	 * 
	 * @method height() 		::Coloca el alto del div contenedor del grafico directamente
	 * @param integer $height 	:: si null su valor por defecto es 100
	 */
	public function height($height){		
		$height = !($height === null) ? "$height" : "100";
		if(!Validate::as_int($height))
			die($height." no es un valor valido");
		$this->varHeight = $height;
	}
	
	/**
	 * 
	 * @method renderer() :: Determina el tipo de grafica que se a plotear
	 * @param string $render
	 * @example renderer($.jqplot.BarRenderer) :: para graficar barras
	 * @example renderer($.jqplot.PieRenderer) :: para graficar barras
	 * @example renderer($.BarRenderer) :: para graficar lineas
	 */
	public function renderer($render){
		if(!in_array($render, array( "$.jqplot.BarRenderer" , "$.jqplot.PieRenderer" , "$.BarRenderer")))
			die($render." no es un valor valido");
		$this->varRenderer = $render;		
	}
	
	/**
	 * 
	 * @method aling() 	::tipo de alineación del div que contendra la grafica
	 * @param $aling 	:: left, right, center
	 */
	public function aling($aling){
		if (!in_array($aling, array( "center", "left" , "right")))
			die($aling." no es un valor valido");
		$this->varAling = $aling;		
	}
	
	/**
	 * 
	 * @method extras() :: Añade a un grafico la propiedad de ZOOM y de visualizar sus valores cuando el cursor pasa sobre ella
	 * @param bool $extras
	 */
	public function extras($extras = false){
		if (!Validate::as_bool($extras))
			die("$extras"." no es un valor valido");
		if($extras){
			$this->varExtras = 'highlighter: {show: true},
   					   cursor: { show: true, zoom: true}';
		} else 
			$this->varExtras = '';
	}
		
	public function showMarker(){
		$this->varShowMarker = "true";
	}
	/**
	 * 
	 * @method tipo() 	::Especifica el tipo de grafico que se desean graficar, asignando automaricamente, render y plugin necesario
	 * @param string 	:: barras , pie, lineas, etc
	 * @todo ir añadiendo los casos faltante
	 */
	public function tipo($render){
		if (!in_array($render, array( "barras" , "torta" , "line")))
			die("$render"." no es un valor valido");
		switch ($render){			
			case 'barras':
				$this->renderer('$.jqplot.BarRenderer');
				$this->script(BARRAS_PLUGINS);
				break;
			case 'torta':
				$this->renderer('$.jqplot.PieRenderer');
				$this->script(TORTAS_PLUGINS);
				break;
			case 'line':
				$this->renderer('$.BarRenderer');
				$this->script(BARRAS_PLUGINS);
				break;
			default:
				break;		
		}
		$this->axes($render);
	}
	
	/**
	 * 
	 * @method divConfig() 	:: Configura parametros visuales del div donde se pinta la grafica
	 * @param string $name 	:: nombre
	 * @param int $width 	:: ancho
	 * @param int $height 	:: alto
	 */
	public function divConfig($name, $width = null, $height = null){
		$this->name($name);
		$this->width($width);
		$this->height($height);
	}
	
	/**
	 * 
	 * @method renderPie() 	:: Dibujar un grafico tipo Torta, por defecto
	 * @param string $title 
	 * @param String $name 	:: si no viene el nombre se pondra un valor numero al random
	 * @param url $url		:: Para la torta la suma de los valores retornados para el URL debe ser 100
	 * 						   los valores deben venir ordenados en un JSON con la siguente forma [ ["label 1" , 20] , ["label 2" , 50] , ["label 3" , 10], ... ] 
	 */
	public function renderPie($title, $url, $name =  null, $flag = false){				
		$this->divConfig($name);
		$this->tipo('torta');		
		$this->title($title);		
		$this->url($url, $flag);
		$this->showMarker();
	}
	
	/**
	 * 
	 * @method renderBar() ::Dibujar un grafico tipo Barras, por defecto
	 * @param string $title
	 * @param url $url
	 * @param String $name :: si no viene el nombre se pondra un valor numero al random
	 */
	public function renderBar($title, $url, $name =  null, $flag = false){				
		$this->divConfig($name);
		$this->title($title);
		$this->tipo('barras');		
		$this->extras(true);
		$this->url($url, $flag);	
	}
	
	/**
	 * 
	 * @method renderLine() ::Dibujar un grafico tipo Lineas, por defecto, si es un grafico en serie debe ser sucedido por la declaracion axes('lineSerie');
	 * @param string $title
	 * @param String $name  :: si no viene el nombre se pondra un valor numero al random
	 * @param url $url		:: Para las Lineas los valores deben venir en pares ordenados
	 * 						   los valores deben venir ordenados en un JSON con la siguente forma [ [x1 , y1] , [x2 , y2] , [x3 , y3], ... ]
	 * 						   en caso de que sean varias series los valores deben venir ordenados en un JSON con la siguente forma [ [[x1 , y1] , [x2 , y2], ...] , [[x1 , y1] , [x2 , y2], ...] , ... ]	 
	 */
	public function renderLine($title, $url, $name =  null, $flag = false){			
		$this->divConfig($name);
		$this->title($title);
		$this->tipo('line');
		$this->extras(true);
		$this->url($url);
		$this->url($url, $flag);
	}
	
	/**
	 * 
	 * @method format() :: Prepara un JSON asociativo tipo llave valor, valido para la usar en esta clase como data
	 * @param Array 	:: Los datos que se queiren pintar deben ser dos columnas 'label' y 'count'   
	 */
	public function format($datos){
		$algo = array();
		foreach ($datos as $index => $count){						
			$algo[$index] = array($count['label'], (int)$count['count']); 
		}
		return $algo;
	}
	
	/**
	 * 
	 * @method axes() :: Configura los Ejes del grafico segun necesite el tipo de grafico
	 * @param String $tipo
	 */
	public function axes($tipo){
		if (!in_array($tipo, array( "line" , "torta" , "barras" , "lineSerie" )))
			die("$tipo"." no es un valor valido");
		switch ($tipo) {
			case 'line':
			case 'torta':
				$this->varAxes = '{}';
				$this->varData = '[data["grafico"]]';
				break;
			case 'barras':
				$this->varAxes = '	{xaxis :{renderer : $.jqplot.CategoryAxisRenderer}}';
				$this->varData = '[data["grafico"]]';
				break;
			case 'lineSerie':
				$this->varAxes = '{xaxis :{renderer : $.jqplot.CategoryAxisRenderer},
								yaxis :{renderer : $.jqplot.CategoryAxisRenderer}}';
				$this->varData = '[data["grafico"]]';
				break;
			default:				
			break;
		}	
	}
	
	public function JSDatatable($datos, $titulos){
		$jsonDataTable = array();
		
		
		foreach ($titulos as $colums => $value){
			$aoColums[$colums]['sTitle'] = $value;
		}
		
		
		if((isset($datos[0]))){
			foreach ($datos as $i => $value){
				$j=0;
				foreach ($value as $row => $value2){				
					if( strtoupper($row ) != 'ID'){
						$aaData[$i][$j] = $value2;												
						$j++;
					}					
				}							
			}
		} else 
			$aaData =  array();
		$jsonDataTable['oLanguage'] = array('sUrl' => '/public/dataTables/Spanish.txt' );
		$jsonDataTable['aaData'] = $aaData;
		$jsonDataTable['aoColumns'] = $aoColums;
		$jsonDataTable['bJQueryUI'] = false;
		$jsonDataTable['bStateSave'] = true;
		$jsonDataTable['sPaginationType'] = "full_numbers";
		
		return $jsonDataTable;
	}
}