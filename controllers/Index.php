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
        echo 'Index Controller / Index Action';
        echo phpinfo();
    }
}