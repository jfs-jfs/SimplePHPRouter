<?php
/**
 * utils.php
 * 
 * This file contains generic utils.
 */

class Utils
{
    // Static class
    private function __construct()
    {
    }

    public static function load_file($file)
    {
        if (file_exists($file)) {
            // Send it
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
    }

    // Validate that sended parameters exists in POST data or GET data
    public static function gather_params(array $vars_to_check_for): array
    {

        $params = array();

        foreach ($vars_to_check_for as $var) {
            
            if (Utils::validateGET($var)) {
                $params[$var] = Utils::get_input_str($var, INPUT_GET);
            } elseif (Utils::validatePOST($var)) {
                $params[$var] = Utils::get_input_str($var, INPUT_POST);
            } else {
                // TODO: Better handle the error
                die();
            }
        }
        
        return $params;
    }

    // Check if vars are set and not empty
    public static function validatePOST(array $vars_to_check_for): bool
    {

        foreach ($vars_to_check_for as $var) {
            if (!isset($_POST[$var]) || empty($_POST[$var])) {
                return false;
                break;
            }
        }
        return true;
    }

    // Check if vars are set and not empty
    public static function validateGET(array $vars_to_check_for): bool
    {

        foreach ($vars_to_check_for as $var) {
            if (!isset($_GET[$var]) || empty($_GET[$var])) {
                return false;
            }
        }
        return true;
    }

    // Common way of extracting strings
    public static function get_input_str(string $var_to_get, int $type_input): string
    {
        return (string) filter_input($type_input, $var_to_get, FILTER_SANITIZE_STRING);
    }

    // Common way of extracting integrers
    public static function get_input_int(string $var_to_get, int $type_input): int
    {
        return (int) filter_input($type_input, $var_to_get, FILTER_SANITIZE_NUMBER_INT);
    }
}
