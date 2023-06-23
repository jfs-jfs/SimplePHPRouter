<?php
/**
 * database.php
 * 
 * Singleton class to connect to a database and intereact with it in a basic level.
 * Meant to be extended (it's functionallity) by composition by other classes giving
 * a more top level interface.
 */
require_once 'models/singleton.php';

class Database extends Singleton
{

    /* @var string Server IP */
    private static $servername = '?????????';
    /* @var string Server username */
    private static $username = '?????????';
    /* @var string Server password */
    private static $password = '*********';
    /* @var string Database name */
    private static $db_name = '?????????';

    /* @var mysqli|null Database connection object */
    private $conn;
    /* @var string Placeholder for results of queries */
    private $last_query_res;

    /**
     * Create the Database object.
     */
    protected function __construct()
    {
        $this->last_query_res = [];

        // Open Connection to the database srv
        $this->conn = new mysqli(
            self::$servername,
            self::$username,
            self::$password,
            self::$db_name
        );

        if ($this->conn->connect_error) {
            die('Failed connection ' . $this->conn->connect_error);
        }
    }

    /**
     * Closes connection and destroy itself
     */
    public function closeConnection()
    {
        $this->conn->close();
        self::destroyInstance();
    }


    /**
     * Generic function to make queries to the database srv where
     * user input can be malformed or malicious.
     */
    public function safe_query(
        string $prepared_statement,
        array $ordered_params,
        string $ordered_params_types,
        bool $store_result = true
    ): bool {
        $params = [];

        // We need the pointers to be able to use the call_user_func_array
        // and use the $stmt->bind_param with dynamic atributes
        // that may vary during runtime
        $params[] = &$ordered_params_types;
        for ($i = 0; $i < count($ordered_params); $i++) {
            $params[] = &$ordered_params[$i];
        }

        // Prepare statement
        $stmt = $this->conn->prepare($prepared_statement);
        if ($stmt === false) return false;

        // Call the $stmt->bind_param with the array of parameters
        call_user_func_array(array($stmt, 'bind_param'), $params);

        // Execute the query
        if ($stmt->execute() === false) return false;

        if ($store_result) {
            // Clean array 
            $this->last_query_res = [];

            // Try to get the result
            $res = $stmt->get_result();
            if ($res === false) return false;

            // Store result
            while (($row = $res->fetch_array(MYSQLI_ASSOC))) {
                array_push($this->last_query_res, $row);
            }
        }

        return true;
    }

    /**
     * Generic function to make quieries where user input has not to do
     * anything with them.
     */
    public function unsafe_query(string $query, bool $store_result = true): bool
    {
        // Execute query
        if (($res = $this->conn->query($query)) === false) return false;

        if ($store_result) {

            // Clean array
            $this->last_query_res = [];

            // Store result
            while (($row = $res->fetch_array(MYSQLI_ASSOC))) {
                array_push($this->last_query_res, $row);
            }
        }

        return true;
    }

    /**
     * Return the result from the last query where the result was saved.
     */
    public function get_result($in_json_format = false)
    {
        if (!$in_json_format) {
            return $this->last_query_res;
        }
        return json_encode($this->last_query_res);
    }
}
