<?php

class Helper_Utils
{
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
        return ($ellipsis && $is_bigger) ? $text.'...' : $text;
    }
}