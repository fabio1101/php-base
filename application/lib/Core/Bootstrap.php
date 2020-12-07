<?php
/**
 * Core class to get the url and transform it into controller/action call. If more values comes after the action
 * (i.e. controller/action/a/b/c) it will pass this values as arguments to the function call. The format to the structure is:
 *  - Only letter (uppercase and lowercase), digits and underscore (_) will be accepted
 *  - Controller name have to be the the first letter uppercase (i.e. Index.php)
 *  - Action name has to be in lowercase all and followed by the word Action (i.e. indexAction())
 *
 * Exceptions will be thrown if:
 *  - File doesnt exist
 *  - Actions doesnt exist in the controller class
 *
 * @package Core
 *
 * @author Fabio Valencia <fabio@triadify.com>
 * @copyright Copyright &copy; 2017, Triadify Inc.
 */
class Core_Bootstrap
{
    /**
     * Allowed elements to avoid throwing exception
     * @var array
     */
    private $allowed = [
        'favicon.ico',
        'public'
    ];

    /**
     * Class name built with each folder opened
     * @var string
     */
    private $controller_name;

    /**
     * The value of the controller part of the url
     * @var string
     */
    private $controller = DEFAULT_CONTROLLER;

    /**
     * Path to locate ddefault forlder or inner depending if it exist
     * @var string
     */
    private $path_to_folder;

    /**
     * The value of the action part of the url
     * @var string
     */
    private $action = DEFAULT_ACTION;

    /**
     * The rest of the values asked in the url
     * @var array
     */
    private $values = [];

    /**
     * Constructor of the class. It get the url and check it until where the folder path exists. After that checks
     * for controller / action (if not exists then go to default ones) and finally if none exist then throw exception
     */
    public function __construct()
    {
        //
        $this->path_to_folder = APP_PATH . '/' . APP_CONTROLLERS;

        // Check if the url is empty in the GET param
        if (!isset($_GET['url'])) {

            // If the url is empty to set the class name as the default controller name without the php extension
            $this->controller_name = rtrim($this->controller, '.php');

            // Finish the execution of this function
            return;
        }

        // If the url is coming in the GET param it continue the process (i.e GET['url'] = '/controller/accion/params')
        $this->controller_name = '';

        // Remove the last / from the url to avoid void element at the end of explode
        $url = rtrim($_GET['url'], '/');

        // Remove characters not allowed in urls
        $url = filter_var($url, FILTER_SANITIZE_URL);

        // explode by /
        $url_segmented = @explode('/', $url);

        // Loop all the segments to find the path to the folder or the controller file
        foreach ($url_segmented as $pos => $segment) {

            // Get the segment and format it with first uppercase and the other lowercase
            $segment = $this->clean(ucfirst(strtolower($segment)));

            // Build the path to check if the folder exists
            $path_to_check = $this->path_to_folder . '/' .$segment;

            // Check if the folder exists
            if (@file_exists($path_to_check) && @is_dir($path_to_check)) {

                // If the fodler exists then append the segment to the actual folder path
                $this->path_to_folder = $this->path_to_folder . '/' .$segment;

                // Add also the segment name to the class name to call (controller name)
                $this->controller_name .= $segment . '_';

                // Remove this segment from, the url segmented array
                unset($url_segmented[$pos]);

            // If not a folder then break the foreach and continue checking
            } else {
                break;
            }
        }

        // Remove any underscore at the end of the class name
        $this->controller_name = rtrim($this->controller_name, '_');

        // Clean up the next segment to check controller name (File)
        $controller = $this->clean(ucfirst(strtolower(reset($url_segmented))));

        // Add the file to the fodler path with php extension
        $file_to_check = $this->path_to_folder . '/' . $controller . '.php';

        // Check if the file exists and if is actually a file. If is true is because the url
        // included the name of the controller file, if not it means that the controller should be
        // the default one and the rest of the segmented url is datapoints
        if (@file_exists($file_to_check) && @is_file($file_to_check)) {

            // Set the new conrtoller file to global var
            $this->controller = $controller . '.php';

            // Remove the first element of the segmented url (The actuall controller file)
            unset($url_segmented[key($url_segmented)]);
        }

        // If the controller name has anything then add an underscore, if not then leave blank.
        // Done to avoid class names like _Index
        if ($this->controller_name) {
            $this->controller_name .= '_';
        }

        // Add the name of the file to the controller class name
        $this->controller_name .= rtrim($this->controller, '.php');

        // Set the values as the rest of the url segmented or empty array if nothing more
        $this->values = array_merge([], $url_segmented);
    }

    /**
     * Method called to start the bootstrap proces to call controller/action
     */
    public function init()
    {
        // Specify the path to the php file that will be loaded (Controller)
        $filepath_to_load = $this->path_to_folder . '/' . $this->controller;

        // Get the action as the first value of the values array
        $action = $this->clean(ucfirst(strtolower(reset($this->values))));

        // If the action is inside the allowed array then just return. this is done to
        // avoid the checkup of this path when is required certain files or folder known as favicon.ico and
        // the public folder. Those are direct files loaded
        if (in_array($action, $this->allowed)) {
            return;
        }

        // Verify if file exists and call require_once to the specific file
        $this->loadFile($this->path_to_folder . '/' . $this->controller);

        // Create a new object of the controller type
        $ctl_name = $this->controller_name . 'Controller';
        $ctl_object = new $ctl_name();

        // Ask if Action name exists as a function
        if (method_exists($ctl_object, $action . APP_ACTION)) {

            // If the action name exist then get the new values to pull the action from there
            $new_values = $this->values;

            // Remove the action from the new values array
            unset($new_values[key($new_values)]);

            // Call the action as a function of the controller passing the new values as arguments
            call_user_func_array(
                array($ctl_object, $action . APP_ACTION),
                $new_values
            );

        // Else if the method in values array doesnt exists then try to use the default action
        } elseif(method_exists($ctl_object, $this->action . APP_ACTION)) {

            // Call the action as a function of the controller passing values as arguments
            call_user_func_array(
                array($ctl_object, $this->action . APP_ACTION),
                $this->values
            );
        } else {

            // Throw exception if the methods evalauted doesnt exists
            throw new Exception("This methods ({$this->action}, {$action}) does not exists in {$this->controller} class");
        }
    }

    /**
     * Clean file name by removing characters different than uppercase and lowwercase letter,
     * numbers and underscore (_) only
     *
     * @param  string $name String to cleanup
     * @return string       Clean name
     */
    private function clean($name)
    {
        return preg_replace("/[^a-zA-Z0-9_]/", '', $name);
    }

    /**
     * Check if the filepath to load exists to call require_once on it. If the file doesnt exists then error is thrown
     * @param  string $filepath
     */
    private function loadFile($filepath)
    {
        // Checks if the filepath correspond to an existing file
        if (@file_exists($filepath) && @is_file($filepath)) {

            // Call require_once to controller (filepath) sent
            require_once($filepath);

        } else {

            // Throw exception
            throw new Exception('File not found when loading url "' . $this->controller . '" (file path "' . $filepath . '")');
        }
    }
}