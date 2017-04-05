<?php
namespace CORE;
use MVC\Model;
use MVC\Utils;
use CORE\Customcode;

Class Menu{

	public function printMenu(){
		$menu = new Customcode('menu');
        $menu->part(array('MAIN'));
        return $menu->printOut('MAIN');
	}
}
