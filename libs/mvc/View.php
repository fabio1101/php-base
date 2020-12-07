<?php
namespace MVC;

require_once('./libs/mvc/Utils.php');
use CORE\CustomCode;
use MVC\Utils as Utils;

class View extends Utils {
	public $url;
	public $MyFile = 'dashboard';
	public $CodePlantilla = '';
	public $PartArray = array ();
	
	function __construct() {
		parent::_construct ();
	}
	
	public function formatOut($content) {
		@ob_end_clean();
		//@header ( 'Content-Type: text/html; charset=UTF-8' );
		echo $content;
		echo $this->timer ();
		die ();
	}
	
	public function Render($content, $layout = false) {
		$layout = $layout ? $layout : $this->MyFile;
		$this->CodePlantilla = new CustomCode ( $layout );
		$this->CodePlantilla->assign ( 'IN', $content );
		$this->formatOut($this->CodePlantilla->printOut());
	}
}
