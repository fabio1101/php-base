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
        $this->view->app_name = APP_NAME;
        $this->view->year = date('Y');
    }
}