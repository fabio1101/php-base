<?php

trait Helper_Trait
{
    // Delimiter keys for encrypting values
    private $keys1 = ['!','~','D','_','a','K'];
    private $keys2 = ['<',',',';','>','h','S'];

    /**
     * Shorten a text to x length and add elipsis at the end
     */
    public function short($data, $lenght = 300, $ellipsis = false) {
        // Check if the string is bigger than the max length
        $is_bigger = strlen($data) > $lenght;

        // If the text is bigger then cut it, else copy it as it is
        $text = ($is_bigger)
            ? substr($data, 0, $lenght)
            : $data;

        // If is required elipsis and is bigger return the text with elipsis,
        // else return as it is
        return ($ellipsis && $is_bigger) ? substr($text, 0, -3).'...' : $text;
    }

    /**
     * Function to upload image
     */
    public function uploadImage()
    {
        $session = Core_Session::getInstance();

        // Destination folder
        $dir = APP_PATH . '/uploads/' . $session->company->getId() . '/';

        // Get the name of the image
        $name = basename($_FILES['image']['name']);

        // Final path of the file
        $upload_file = $dir . $name;

        // File extensions allowed by the server
        $allowed_files = ['image/jpeg','image/png','image/bmp','image/gif'];

        // If the file extension is allowed by the server
        if (in_array($_FILES['image']['type'], $allowed_files)) {

            // Move the file from the temporary folder to the destination path
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {

                return ($name);
        }   else {

                return false;
            }
        } else {

            return false;
        }
    }

    /**
     * Function to remove the server image
     */
    public function deleteImage($name)
    {
        $session = Core_Session::getInstance();

        $dir = APP_PATH . '/uploads/' . $session->company->getId() . '/';

        unlink($dir.$name);
    }

    /**
     * Function to ofuscate a value (encryption)
     */
    protected function encrypt($to_encrypt)
    {
        $value = serialize($to_encrypt);

        $key1 = $this->keys1[array_rand($this->keys1)];
        $key2 = $this->keys2[array_rand($this->keys2)];

        return @base64_encode($this->randomize(rand(5,10)) . $key1 . $to_encrypt . $key2 . $this->randomize(rand(5,10)));
    }

    /**
     * Function to get the real data of an encrypted value
     */
    protected function decrypt($to_decrypt)
    {
        // The pattern to use to decrypt. is basically a group between the first delimiter (keys1)
        // and the second one (keys2) /["keys1"](VALUE)["keys2"]/
        $pattern = '/['.implode($this->keys1).'](.+)['.implode($this->keys2).']/';

        // Get the match from the pattern
        preg_match($pattern, @base64_decode($to_decrypt), $match);

        // Return position 1 of the array of mathces (Position for clean value to return)
        $value = $match[1];
        return $value;
    }

    /**
     * Get a random string of allowed chars
     */
    protected function randomize($size = 10)
    {
        // Set of data to build the password
        $all = 'bcdefgjkmnpqrstuvwxyz';
        $all .= 'ABCEFGHJMNPQRTUVWXYZ';
        $all .= '23456789';
        $all .= '@#';

        $random = '';

        // Build and array of possible chars
        $all = str_split($all);

        // Loop the chars and add a random one
        for($i = 0; $i < $size; $i++){
            $random .= $all[array_rand($all)];
        }

        // Shuffle the string to give it more random behavior
        $random = str_shuffle($random);

        return $random;
    }

    /**
     * Function to encode string values to csv field format to avoid braking the file.
     * This will clean chars like " or \n
     */
    protected function encodeCSVField($string)
    {
        if(strpos($string, '"') !== false || strpos($string, "\n") !== false) {
            $string = str_replace('"', '""', $string);
            $string = str_replace(PHP_EOL, '', $string);
        }
        return '"'.($string).'"';
    }

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
}