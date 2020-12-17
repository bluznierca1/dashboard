<?php

if( !file_exists('config.php') ) {
    die('config file does not exist');
}

if( !file_exists('routes/routes.php' ) ) {
    die('routes.php not found.');
}

require_once('config.php');
require_once('routes/routes.php');

// Handle request
$request = new Dashboard\Request\Request($_SERVER['REQUEST_URI']);