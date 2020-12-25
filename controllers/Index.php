<?php

class IndexController extends Core_Controller
{
    /**
     * Undocumented function
     */
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();
    }

    function IndexAction(): void
    {
        $this->view->content = '<hr>';
        $this->view->render();
    }
}