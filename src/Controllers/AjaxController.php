<?php

namespace Dashboard\Controllers;

use Dashboard\Helpers\ValidationHelper;
use Dashboard\Traits\ControllerTrait;

class AjaxController extends Controller {

    use ControllerTrait;

    /**
     * Delegate proper method for handling AJAX request based on provided action
     * return empty JSON if method not found
     */
    public function handleAjax(): void {
        $postData = $this->request->getPostRequestData();

        // Detect action and trigger it if exists
        if( !empty($postData) && isset($postData['action']) && method_exists($this, $postData['action']) ) {
            $method = $postData['action'];
            $this->$method();
        }

        $this->jsonResponse([]);

    }

    /**
     * Build data for chart for given time range
     * Return as JSON
     */
    protected function getChartDataByRange(): void {
        $postData = $postData = $this->request->getPostRequestData();

        // validate
        $expectedFields = ['datepicker_date_start', 'datepicker_date_end'];

        $isValidationPassed = false;
        if( ValidationHelper::areExpectedFieldsInArray($expectedFields, $postData) ) {
            $dateStart = filter_var($postData['datepicker_date_start'], FILTER_SANITIZE_STRING);
            $dateTo = filter_var($postData['datepicker_date_end'], FILTER_SANITIZE_STRING);
            if( ValidationHelper::isValueCorrectDateFormat($dateStart) && ValidationHelper::isValueCorrectDateFormat($dateTo) ) {

                if( strtotime($dateTo) > strtotime($dateStart) ) {
                    $isValidationPassed = true;
                }

            }
        }

        $currentDate = date('Y-m-d');

        $data['chart']['customers'] = [];
        if( $isValidationPassed ) {

            $numberOfDaysBetweenDates = round((strtotime($postData['datepicker_date_end']) - strtotime($dateStart)) / (60 * 60 * 24));
            $data['chart']['customers'] = $this->getCustomersDataUntilDate($postData['datepicker_date_end']);
            $data['chart']['customers'] = $this->prepareDataForChartCustomers( $data['chart']['customers'], $numberOfDaysBetweenDates, $currentDate);

            $data['chart']['orders'] = $this->getOrdersDataForDateRange($dateStart, $postData['datepicker_date_end']);
            $data['chart']['orders'] = $this->prepareDataForChartOrder($data['chart']['orders'], $numberOfDaysBetweenDates, $dateTo);

            $this->jsonResponse($data);

        }

        $this->jsonResponse([]);
    }

    public function jsonResponse( array $data = [] ): void {
        echo json_encode($data);
        die();
    }
}