<?php

/**
 * core.php
 * Global variables for the system
 */

$core = array(
    "request_method" => $_SERVER['REQUEST_METHOD'],
    "link" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
    "GET_valid_paths" => array(
        // "path" => [function, [parameters]]
    ),
    "POST_valid_paths" => array(
        // "path" => [function, [parameters]]
    ),
    "PUT_valid_paths" => array(
        // "path" => [function, [parameters]]
    )
);

// Added afterwards because I need to have $config['link'] processed
// 1. Separate from the link the path in the url
$core["path"] = parse_url($core['link'], PHP_URL_PATH);
// 2. Select the query to get the GET params
$core["params"] = parse_url($core['link'], PHP_URL_QUERY);

// So can be accessed from everywhere
$GLOBALS['core'] = $core;


// Twig configuration
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('views/');
$twig = new \Twig\Environment($loader, [
    'cache' => 'cache/',
]);

// So it can be accessed everywhere
$GLOBALS['template_engine'] = $twig;