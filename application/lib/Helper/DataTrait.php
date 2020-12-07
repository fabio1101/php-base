<?php

trait Helper_DataTrait
{
    // Trait to use other helper function available on the helpers
    use Core_Trait;

    /**
     * Function in trait to Allow autoloading the properties based on the names of the sent data
     */
    public function load($data)
    {
        // Check if the data is an array and if not throw an exception
        if (!is_array($data)) {
            throw new Exception("The load function of the DataTrait requires an array of datapoints");
        }

        // Loop all the data array
        foreach ($data as $name => $value) {

            // Build the method name (Camel Case)
            $method = 'set' . $this->toCamelCase($name);

            // Check if the method exists and if not then throw exception
            if (!method_exists($this, $method)) {
                $class_name = get_class($this);
                throw new Exception("The method $method does not exist in the trait for class $class_name");
            }

            // If all looks good then execute the function in this class
            $this->$method($value);
        }
    }

    /**
     * Private function to convert a camelCase name into a name based on underscores (this_is_an_example)
     */
    private function fromCamelCase($string)
    {
        return preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", trim($string));
    }

    /**
     * Private function to convert from unserscore based names into CamelCase
     */
    private function toCamelCase($text, $lcfirst = false) {

        // clean up the action
        $text = trim($text);
        $text = strtolower($text);
        $text = str_replace('_', ' ', $text);
        $text = ucwords($text);
        $text = str_replace(' ', '', $text);

        // only lower the first word if requested
        if ($lcfirst) {
            $text = lcfirst($text);
        }

        return $text;
    }
}
