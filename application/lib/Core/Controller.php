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
        $this->view->DATE = date('Y');

        // Set the main values to the layout
        $this->view->MENU = $this->getMenuByRole();
        $this->view->GTAG_HEADER = GTAG_HEADER;
        $this->view->GTAG_BODY = GTAG_BODY;
    }

    /****************************************************
     * Auth Section for all controllers
     ****************************************************/

    /**
     * Verify if we have a user logged, if not the make all the logout process
     */
    protected function verifyLogged()
    {
        if ($this->isLogged()) {
            return true;

        } else {
            $this->logout();
        }
    }

    /**
     * Check if any user is logged and return true or false.
     */
    protected function isLogged()
    {
        $user = $this->session->user;
        $company = $this->session->company;

        if ($user && $company) {
            return true;
        }

        return false;
    }

    /**
     * Remove objects from session
     */
    protected function logout()
    {
        unset($this->session->user);
        unset($this->session->company);

        // Redirect home
        $this->redirect();
    }

    /**
     * Check permission on user object to redirect to specific home page
     */
    protected function redirectHome()
    {
        if ($this->isLogged()) {

            $this->redirect('/index/home');

        } else {

            $this->logout();
        }
    }

    /****************************************************
     * Helper functions for all controllers
     ****************************************************/

    /**
     * Make a redirect from PHP to a specific path, this path can have var values in his structure and
     * those will be converted to POST to avoid showing those values in the browser url.
     *
     * @var $path the url to redirect to. (i.e. /controller/action?var=1)
     */
    protected function redirect($path = '/')
    {
        @ob_clean();

        // Check if the url has vars to send
        $has_vars = preg_match('/\?/', $path);

        // If has vars to send then explode by symbol ?, else build and array with dir and empty vars
        if ($has_vars) {
            $data = explode('?', $path);

        } else {
            $data = [$path, ''];
        }

        $address = $data[0];
        $vars    = $data[1];

        // Build the js code to redirect
        $header = '<script src="/public/_jquery/jquery.min.js"></script>'.
                  '<script src="/public/_js/functions.js"></script>';
        $code = "<body><script>redirect('$address','$vars');</script></body>";
        echo $header . $code;
        die;
    }

    /**
     * Return the value of a variable checking first GET value, then POST value and then returns null
     */
    protected function getVariable($var_name)
    {
        // Get global GET and POST vars
        global $_GET, $_POST;

        return isset($_GET[$var_name])
            ? $_GET[$var_name]
            : (isset($_POST[$var_name])
                ? $_POST[$var_name]
                : NULL);
    }

    /**
     * Get all the values from the POST global var
     */
    protected function getAllPOST()
    {
        // Get the global post vars
        global $_POST;
        $vars = null;

        // Loop them and build an array by name => value
        foreach($_POST as $name => $val) {
            $vars[$name] = $val;
        }

        return $vars;
    }

    /**
     * Get an array or a value to codify in json format and print it. Used for ajax calls
     */
    protected function jsonOut($data = false)
    {
        // Clean the output buffer and put the respective data as json format
        @ob_clean();
        @header('Cache-Control: no-cache, must-revalidate');
        @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        @header('Content-type: application/json; charset=UTF-8');
        echo json_encode($data);
        die;
    }

    /**
     * Depending on the role of the user object in session build the menu accordingly
     */
    protected function getMenuByRole()
    {
        if ($this->isLogged()) {
            // Build a new menu object with the user
            $menu = new Core_Menu($this->session->user);

            // Get the code of the menu
            $menu_code = $menu->getMenu();

        } else {
            // If is not logged then return empty or menu for global users
            $menu_code = '';
        }

        return $menu_code;
    }

    /**
     * Function to reload the language structure in session. This allows to reload not only when going to /es but
     * wherever in the code this line exist (home controllers for admins/users)
     */
    public function reloadLanguage()
    {
        // Build dependencies for the service
        $service_dep = [
            'model' => Core_Model::getInstance()
        ];

        // Build a new service object and get the terms with the existing language code
        $service = new Auth_Service($service_dep);
        $results = $service->getTerms($this->session->language);

        // Loop the terms and set them in a regular array (key is term, value is term text)
        foreach ($results as $term) {
            $language[$term['term']] = $term['text'];
        }

        // Set in session the term list
        $this->session->terms = $language;
    }
}