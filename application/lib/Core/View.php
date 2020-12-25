<?php

class Core_View
{
    /**
     * Render the actual view with the values setted to this object
     */
    public function render($template = false)
    {
        // If not specified then use the default template setted in config
        $template = ($template) ?: APP_LAYOUT;
        $html = new Core_Template($template);

        // Loop object properties and replace in view if the properties are strings or numbers
        foreach ($this as $var_name => $value) {
            if (is_string($value) || is_numeric($value)) {
                $html->assign($var_name, $value);
            }
        }

        // Render the template replaced
        $this->formatOut($html->printOut());
    }

    /**
     * Clean the output stream and show the content
     */
    private function formatOut($content)
    {
        @ob_end_clean();
        @header('Content-Type: text/html; charset=UTF-8');
        echo $content;

        // Check if messages are pending to show and show it
        echo $this->renderMessage();

        die;
    }

    /**
     * Check if is needed to show a message pending in session and add the script
     */
    private function renderMessage()
    {
        // Gets the session instance
        $session = Core_Session::getInstance();

        // Check if message is pending to be shown
        if ($session->has_msg) {

            // Get message type and content
            $msg_type = $session->msg_type;
            $msg = $session->msg;

            // Clean the session datapoints
            unset($session->has_msg);
            unset($session->msg_type);
            unset($session->msg);

            // return the script with the message depending on type
            return "<script>toastr.$msg_type('$msg')</script>";
        }

        return '';
    }
}
