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

        // Set default Section name
        $this->view->SECTION = 'Home';
    }

    function IndexAction(): void
    {
        // Build the login box code and set it to the content placeholder
        $this->view->CONTENT = '<hr>';

        // Render the view with the info
        $this->view->render();
    }
}