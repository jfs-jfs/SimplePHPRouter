<?php
/**
 * router.php
 * 
 * This file contains the logic necessary for the aplication to run.
 * Here we find the Applicatin class that will be the ruter of the system.
 */

require_once 'core.php';

class Application
{
    /**@const string for indexation in $GLOBAL['core'] */
    const GET_TYPE = "GET_valid_paths";
    const POST_TYPE = "POST_valid_paths";
    const PUT_TYPE = "PUT_valid_paths";

    /**@var array to hold the core variables */
    private $core;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->core = $GLOBALS['core'];
    }

    /**
     * Debugging function
     */
    public function printConf()
    {
        echo var_dump($this->core);
    }

    /**
     * Main function of the application. Last thing to be called in
     * the index.php execution flow.
     */
    public function execute()
    {
        $valid_paths = [];
        $request_method = $_SERVER['REQUEST_METHOD'];

        // Get the corresponding valid paths
        switch ($request_method) {
            case 'POST':
                $valid_paths = $this->core[self::POST_TYPE];
                break;

            case 'GET':
                $valid_paths = $this->core[self::GET_TYPE];
                break;

            case 'PUT':
                // TODO: $input = $_PUT;
                $valid_paths = $this->core[self::PUT_TYPE];
                break;

            default:
                die('Unknown method');
                break;
        }

        // Check if the requested path is a defined path
        if (array_key_exists($this->core['path'], $valid_paths)) {

            // With this path what do I need?
            $key = $this->core['path'];
            $func = $valid_paths[$key][0];
            $needed_params = $valid_paths[$key][1];

            // Var to send to the function
            $req = Utils::gather_params($needed_params);

            // Do the described functionality
            $func($req);
        }
    }

    /**
     * Inernal function to add paths to the system.
     */
    private function add_path(String $path_vars, $function, $parameters, $request_type)
    {
        // Append to core
        $this->core[$request_type][$path_vars] = [$function, $parameters];
    }

    /**
     * Userfriendly function to add a get path
     */
    public function get(String $path, $parameters, $function)
    {
        $this->add_path($path, $function, $parameters, self::GET_TYPE);
    }

    /**
     * Userfriendly function to add a post path
     */
    public function post(String $path, $parameters, $function)
    {
        $this->add_path($path, $function, $parameters, self::POST_TYPE);
    }

    /**
     * Userfriendly function to add a put path
     */
    public function put(String $path, $parameters, $function)
    {
        $this->add_path($path, $function, $parameters, self::PUT_TYPE);
    }
}
