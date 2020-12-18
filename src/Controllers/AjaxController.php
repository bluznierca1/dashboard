<?php

namespace Dashboard\Controllers;

class AjaxController extends Controller {

    public function hello() {
        echo 'HELLO from ajax controller <br />';
    }

    public function handleAjax() {
        echo json_encode(['hello' => 'world']);
    }
}