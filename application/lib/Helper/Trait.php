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
     * Private function to convert a camelCase name into a name based on underscores (this_is_an_example)
     */
    protected function fromCamelCase($string)
    {
        return preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", trim($string));
    }

    /**
     * Private function to convert from unserscore based names into CamelCase
     */
    protected function toCamelCase($text, $lcfirst = false) {

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