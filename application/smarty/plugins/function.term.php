<?php
/**
 * Smarty function to return the translated text from database based on the term name
 *
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 *
 * @return string|null
 */
function smarty_function_term($params, $template)
{
    // Get the key name of the translation
    $term_name = $params['key'];

    // If we are debuggin the terms then just return the term name
    if (TERM_DEBUG) {
        return $term_name;
    }

    // Get the session object
    $session = Core_Session::getInstance();

    // Get the list of terms from session
    $terms = $session->terms;

    // If the term does not exist then log exception and return empty text
    if (!isset($terms[$term_name])) {

        // Log the missing translation text in the error log
        Core_Logger::log(new Exception("The translation $term_name does not exists in template {$template->getSource()->resource}"));

        // Return empty string
        return '';
    }

    return $terms[$term_name];
}
