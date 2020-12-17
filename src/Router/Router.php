<?php

namespace Dashboard\Router;

class Router {

    /**
     * Key -> method name (based on path/filename)
     * Value -> controller
     *
     * @var array
     */
    private static $registeredRoutesWithControllers = [];

    /**
     * @return array
     */
    public static function getRegisteredRoutesWithControllers(): array {
        return self::$registeredRoutesWithControllers;
    }

    /**
     * Register controller and its method for a path
     *
     * @param string $path
     * @param string $controller
     * @param string $method
     */
    public static function registerPathWithController( string $path = '', string $controller = '', string $method = ''): void {

        $path = str_replace('/', '_', $path);

        if( !isset(self::$registeredRoutesWithControllers[$path] ) ) {

            self::$registeredRoutesWithControllers[$path] = [
                'controller'    => $controller,
                'method'        => $method
            ];

        } else {
            die('path is already registered.');
        }

    }

}