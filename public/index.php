<?php
/**
 * index.php
 * 
 * Example of use
 */

// Go Back
chdir('..');
require_once 'core.php';
require_once 'models/router.php';
require_once 'models/utils.php';

$app = new Application();

$app->get('/', [], function ($res) {

    // Grab engine
    $te = $GLOBALS['template_engine'];


    echo $te->render("test.twig");

});

$app->execute();
