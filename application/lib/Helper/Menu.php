<?php

Class Helper_Menu
{
    /**
     * @var Auth_Db_User
     */
    protected $user;

    /**
     * @return Auth_Db_User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Auth_Db_User $user
     */
    public function setUser(Auth_Db_User $user)
    {
        $this->user = $user;
    }

    /**
     * Set the required dependency
     */
    public function __construct($user)
    {
        $this->setUser($user);
    }

    /**
     * Build and return the code for the menu depending on permission
     */
    public function getMenu()
    {
        // Build a new html code for the template
        $menu = new Core_Template('menu');

        // Set the user to the view to use it in the markup
        $menu->assign('user', $this->getUser());

        return $menu->printOut();
    }
}
