<?php

namespace Dashboard\Controllers;

use Dashboard\Interfaces\ControllerInterface;

class DashboardController extends Controller {

    public function index() {

        $data['pageTitle'] = 'Hello World';
        $this->renderView( $data );
    }

}