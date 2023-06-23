<?php

/**
 * singleton.php
 * 
 * This class contains an abstract class to make its sons singletons.
 * A class that heredates from this one only needs to set it's
 * constructor to protected and it will be a singleton.
 */

abstract class Singleton
{

    // Shared array with the pointers to the created classes that
    // need to be singletons
    private static $instances = [];

    // Constructor
    protected function __construct()
    {
    }

    // Classic creation singleton interfce
    final public static function getInstance()
    {
        // Get which class is the one we try to create
        $class_to_create = get_called_class();

        // If already exists in the array return the pointer
        if (!isset(self::$instances[$class_to_create])) {
            self::$instances[$class_to_create] = new $class_to_create();
        }

        return self::$instances[$class_to_create];
    }

    // Destroy interface
    final public static function destroyInstance()
    {
        // Get the name of the class to delete
        $class_to_delete = get_called_class();

        // Try to dereference it to delete it
        if (isset(self::$instances[$class_to_delete])) {
            self::$instances[$class_to_delete] = null;
            unset(self::$instances[$class_to_delete]);
        }
    }

    // Cloning does nothing
    final private function __clone()
    {
    }
}
