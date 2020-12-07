<?php
class Core_Template
{
    /**
     * The smarty object to render the template
     *
     * @var Smarty
     */
    protected $smarty;

    /**
     * The template full name (inlcuding extension)
     *
     * @var string
     */
    protected $template;

    /**
     * Constructor function with the template name as parameter (Only file name with no extension)
     *
     * @param string $template
     */
    public function __construct($template)
    {
        // Validate if the template name was not sent to throw exception
        if (!$template) {
            throw new Exception("This class requires a template name");
        }

        // Set the template name adding the extension
        $this->template = $template . '.html';

        // Build the new smarty object to render
        $this->smarty = new Smarty();

        // Configure the object with the specific folders
        $this->smarty->setTemplateDir('public/templates');
        $this->smarty->setCompileDir('application/smarty/compiled');
        $this->smarty->setCacheDir('application/smarty/cache');
        $this->smarty->setConfigDir('application/smarty/configs');
    }

    /**
     * Function to assign a new variable to the template
     *
     * @param string $name
     * @param $value
     *
     * @return void
     */
    public function assign($name, $value)
    {
        $this->smarty->assign($name, $value);
    }

    /**
     * Function to render the html code with the variables assigned
     *
     * @return string
     */
    public function printOut()
    {
        return $this->smarty->fetch($this->template);
    }
}
