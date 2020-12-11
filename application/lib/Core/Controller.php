<?php

class Core_Controller
{
    /**
     * @var Core_View
     */
    protected $view;

    /**
     * @var Core_Session
     */
    protected $session;

    function __construct()
    {
        $this->view = new Core_View();
        $this->session = Core_Session::getInstance();
        $this->setup();
    }

    /**
     * Function to setup basic variables to all the controllers
     */
    private function setup()
    {
        $this->view->APP_NAME = APP_NAME;
        $this->view->APP_NAME_SHORT = APP_NAME_SHORT;
        $this->view->YEAR = date('Y');
    }
}