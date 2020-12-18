<?php

/*
 * That file would be excluded from GIT but that project is not going live.
 * Even if that happens, it will look different :)
 */

date_default_timezone_set('Europe/Copenhagen');

!defined('IS_DEV') ? define('IS_DEV', true) : null;

if( IS_DEV ) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

!defined('URL_PREFIX') ? define('URL_PREFIX', IS_DEV ? 'dashboard' : 'SOME_OTHER_PREFIX_OR_NONE') : null;
!defined('APP_ROOT') ? define('APP_ROOT', __DIR__ ) : null;

!defined( 'SRC_ROOT' ) ? define('SRC_ROOT', APP_ROOT . '/src/') : null;
!defined( 'TEMPLATES_PATH' ) ? define('TEMPLATES_PATH', APP_ROOT . '/templates/') : null;
!defined('HOME_URL') ? define('HOME_URL', IS_DEV ? 'http://localhost:8080/dashboard' : 'SOME_OTHER_LINK') : null;

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