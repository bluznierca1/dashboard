<?php

namespace Dashboard\Controllers;

use Dashboard\Helpers\RedirectHelper;
use Dashboard\Interfaces\ControllerInterface;
use Dashboard\Mocker\Mocker;
use Dashboard\Request\Request;
use Dashboard\Views\View;

class Controller implements ControllerInterface {

    protected $request;

    public function __construct( Request $request, string $method ) {
        $this->request = $request;

        if( method_exists($this, $method) ) {
            $this->$method();
        } else {
            $this->notFound();
        }

    }

    /**
     * Extract class name (without namespaces)
     * Calling static::class inside this method would always return 'Controller'
     *
     * @param string $namespace
     * @return string
     */
    public static function extractClassName( string $namespace = '' ): string {
        $explodedNamespaces = explode('\\', $namespace);
        return !empty($explodedNamespaces) ? end($explodedNamespaces) : '';
    }

    /**
     * Trigger View
     *
     * @param array $data
     */
    public function renderView( array $data = [] ): void {

        $controllerName = self::extractClassName(static::class);

        $view = new View();

        echo $view->renderView($controllerName, 'index.php', $data);

    }

    /**
     * Method assigned to the route was not found.
     * Redirect to home page.
     */
    public function notFound(): void {
        RedirectHelper::redirectToHomePage();
    }

    /**
     * Erase all records from DB
     * Fill DB with new records
     */
    public function refillDb(): void {

        $mocker = new Mocker();

        if( $mocker->clearDbRecords() ) {
            echo 'Successfully erased DB records. <br />';

            if( $mocker->fillDbWithRecords() ) {
                echo 'Db is successfully refilled.';
                die();
            }

            echo 'Something went wrong while filling DB... Erasing all records.';
            $mocker->clearDbRecords();
            die();
        }

        echo 'Could not erase DB records.';

    }

}