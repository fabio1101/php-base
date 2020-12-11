<?php

class Core_Bootstrap_Launcher
{
    /**
     * Undocumented function
     *
     * @param Core_Bootstrap_Url $url
     * @return void
     */
    public function launch(Core_Bootstrap_Url $url)
    {
        // If the url action is static content then finish execution
        if ($url->isStatic()) {
            return;
        }

        // Verify if file exists and call require_once to the specific file
        $this->loadFile($url);

        // Create a new object of the controller type
        $ctl_name = $url->getControllerClassName();
        $ctl_object = new $ctl_name();

        // Ask if Action name exists as a function
        if (method_exists($ctl_object, $url->getAction())) {

            // Call the action as a function of the controller passing the params as arguments
            call_user_func_array(
                array($ctl_object, $url->getAction()),
                $url->getParams()
            );

        } else {

            // Throw exception if the methods evalauted doesnt exists
            throw new Exception("This methods ({$url->getAction()}) does not exists in {$url->getController()} class");
        }
    }

    /**
     * Undocumented function
     *
     * @param Core_Bootstrap_Url $url
     * @return void
     */
    private function loadFile(Core_Bootstrap_Url $url): void
    {
        // Checks if the filepath correspond to an existing file
        if (file_exists($url->getControllerFilePath()) && is_file($url->getControllerFilePath())) {

            // Call require_once to controller (filepath) sent
            require_once($url->getControllerFilePath());

        } else {

            // Throw exception
            throw new Exception('File not found when loading controller "' . $url->getController() . '" (file path "' . $url->getControllerFilePath() . '")');
        }
    }
}