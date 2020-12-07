<?php

use CORE\CustomCode;
use MVC\Controller;
use inner\IndexClass;

class Index extends Controller{

    private $index;

    function __construct() {
        parent::__construct();
        $this->index = new IndexClass();
        $this->content['SECTION']    = 'Inicio';
    }


    function IndexAction(){
        $this->content['HEADERS'] = '';
        $this->content['SECTION'] = '';
        $this->content['CONTENT'] = 'THE CODE!';
        $this->content['MENU'] = $this->menu->printMenu();
        $this->view->Render($this->content, 'layout');
    }
}