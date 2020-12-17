<?php

date_default_timezone_set('Europe/Copenhagen');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

!defined('URL_PREFIX') ? define('URL_PREFIX', 'dashboard') : null;
!defined('APP_ROOT') ? define('APP_ROOT', __DIR__ ) : null;

!defined( 'SRC_ROOT' ) ? define('SRC_ROOT', APP_ROOT . '/src/') : null;
!defined( 'TEMPLATES_PATH' ) ? define('TEMPLATES_PATH', APP_ROOT . '/templates/') : null;
!defined('HOME_URL') ? define('HOME_URL', 'http://localhost:8080/dashboard') : null;

spl_autoload_register( static function($class) {

    $classPath = str_replace('\\', '/', $class) . '.php';

    // Dashboard is prefix in namespace, replace with src (only first occurrence)
    $classPath = substr_replace($classPath, 'src', strpos($class, 'Dashboard'), strlen('Dashboard'));

    if( file_exists($classPath) ) {
        require $classPath;
    } else {
        die('Path: ' . $classPath . ' for class: ' . $class . ' does not exist');
    }

});