<?php

class Core_Bootstrap_Url
{
    /**
     * Controller filename to include, default is Index
     *
     * @var string
     */
    protected $controller = 'Index';

    /**
     * Controller class name
     *
     * @var string
     */
    protected $controller_class_name;

    /**
     * Controller file path to load the file
     *
     * @var string
     */
    protected $controller_file_path;

    /**
     * Action function name, default is Index
     *
     * @var string
     */
    protected $action = 'Index';

    /**
     * Folder path to load the controller files
     *
     * @var string
     */
    protected $controller_folder_path = APP_PATH . '/controllers';

    /**
     * This holds the extra parameters of the url that will be sent as funciton parameters
     *
     * @var array
     */
    protected $params = [];

    /**
     * Property that specifies if the url is static content to avoid
     * controller/action parsing
     *
     * @var boolean
     */
    protected $static = false;

    /**
     * Allowed elements to avoid throwing exception (Static content)
     * @var array
     */
    protected $static_patterns = [
        '.ico',
        'public'
    ];

    /**
     * @param string|null $url
     */
    public function __construct(?string $url)
    {
        // Check if the url is empty to use default controller/action
        if (!$url) {

            // If empty url then set class name and path and finish execution
            $this->controller_class_name = $this->controller;
            $this->controller_file_path = $this->controller_folder_path . '/' . $this->controller . '.php';
            return;
        }

        // Check if this is static content and if so then make it static and return
        if ($this->isStaticContent($url)) {

            $this->static = true;
            return;
        }

        // Parse URL to set controller name, path and class name
        $this->parseController($url);

        // Parse rest of URL to set action name
        $this->parseAction($url);
    }

    protected function parseController(string $url)
    {
        // Remove the last / and any white spaces from the url to avoid void
        // element at the end of explode and wrong final value
        $url = rtrim($url, '/ ');

        // Remove characters not allowed in urls
        $url = filter_var($url, FILTER_SANITIZE_URL);

        // explode by /
        $url_segmented = explode('/', $url);

        $this->controller_file_path = $this->controller_folder_path;

        // Loop all the segments to find the path to the folder or the controller file
        foreach ($url_segmented as $position => $segment) {
            // Get the segment and format it with first uppercase and the rest lowercase
            $segment = $this->segmentFormat($segment);

            // Build the path to check if the folder exists
            $path_to_check = $this->controller_file_path . '/' . $segment;

            // Check if the folder exists
            if (file_exists($path_to_check) && is_dir($path_to_check)) {

                // If the fodler exists then append the segment to the actual folder path
                $this->controller_file_path .= '/' . $segment;

                // Add also the segment name to the class name to call (controller name)
                $this->controller_class_name .= $segment . '_';

                // Remove this segment from, the url segmented array
                unset($url_segmented[$position]);

            // If not a folder then break the foreach and process controller/action
            } else {

                break;
            }
        }

        // Remove any underscore at the end of the class name
        $this->controller_class_name = rtrim($this->controller_class_name, '_');

        // Reset segment array (also reset array keys)
        $url_segmented = array_merge([], $url_segmented);

        // Clean up the next segment to check controller name (File)
        $test_controller = $this->segmentFormat($url_segmented[0] ?? '');

        // Add the file to the folder path with php extension
        $this->controller_file_path .= "/{$test_controller}.php";

        // Set the new controller file to global var
        $this->controller = $test_controller;

        // Remove the first element of the segmented url (The actual controller file)
        unset($url_segmented[0]);

        // If the controller name has anything then add an underscore, if not then leave blank.
        // Done to avoid class names like _Index
        if ($this->controller_class_name) {
            $this->controller_class_name .= '_';
        }

        // Add the name of the file to the controller class name
        $this->controller_class_name .= $this->controller;

        // Set the values as the rest of the url segmented or empty array if nothing more
        $this->params = array_merge([], $url_segmented);
    }

    protected function parseAction(string $url)
    {
        // If no more params then use the default action
        if (!$this->params) {
            return;
        }

        // Get the first position of the params as the action
        $this->action = $this->segmentFormat($this->params[0]);

        unset($this->params[0]);

        // Set the values as the rest of the url segmented or empty array if nothing more
        $this->params = array_merge([], $this->params);;

    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @return boolean
     */
    private function isStaticContent(string $url): bool
    {
        $regex = implode('|',$this->static_patterns);

        return preg_match("/({$regex})/", $url);
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return string
     */
    private function segmentFormat(string $name): string
    {
        $name = preg_replace("/[^a-zA-Z0-9_]/", '', $name);

        return ucfirst(strtolower(trim($name)));
    }

    /**
     * Get action function name, default is Index
     *
     * @return  string
     */
    public function getAction()
    {
        return $this->action . 'Action';
    }

    /**
     * Get controller file path to load the file
     *
     * @return  string
     */
    public function getControllerFilePath()
    {
        return $this->controller_file_path;
    }

    /**
     * Get controller filename to include, default is Index
     *
     * @return  string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Get controller class name
     *
     * @return  string
     */
    public function getControllerClassName()
    {
        return $this->controller_class_name . 'Controller';
    }

    /**
     * Get this holds the extra parameters of the url that will be sent as funciton parameters
     *
     * @return  array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Returns if the content to serve is a static file
     *
     * @return  boolean
     */
    public function isStatic()
    {
        return $this->static;
    }
}