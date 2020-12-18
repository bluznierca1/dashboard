<?php

namespace Dashboard\Controllers;

class AjaxController extends Controller {

    public function hello() {
        echo 'HELLO from ajax controller <br />';
    }

    public function handleAjax(): void {
        $postData = $this->request->getPostRequestData();

        // Detect action and trigger it if exists
        if( !empty($postData) && isset($postData['action']) && method_exists($this, $postData['action']) ) {
            $method = $postData['action'];
            $this->$method();
        }

        $this->jsonResponse([]);

    }

    protected function getChartDataByRange() {
        $postData = $postData = $this->request->getPostRequestData();
        $this->jsonResponse(['test' => 'success']);
    }

    public function jsonResponse( array $data = [] ): void {
        echo json_encode($data);
        die();
    }
}