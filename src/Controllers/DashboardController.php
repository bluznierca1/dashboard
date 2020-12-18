<?php

namespace Dashboard\Controllers;

use Dashboard\Interfaces\ControllerInterface;
use Dashboard\Models\CustomerEntity;
use Dashboard\Models\OrderEntity;
use Dashboard\Models\OrderItemsEntity;
use Dashboard\Traits\ControllerTrait;

class DashboardController extends Controller {

    use ControllerTrait;

    public function index(): void {

        $currentDate = date('Y-m-d');
        $dateMonthAgo = date('Y-m-d', strtotime('-1 month'));

        $data['pageTitle'] = 'Hello World';
        $data['dateNow'] = $currentDate;
        $data['dateMonthAgo'] = $dateMonthAgo;

        $numberOfDaysBetweenDates = round((strtotime($currentDate) - strtotime($dateMonthAgo)) / (60 * 60 * 24));

        // On init fetch only last month
        $data['chart']['customers'] = $this->getCustomersDataUntilDate($currentDate);
        $data['chart']['customers'] = $this->prepareDataForChartCustomers( $data['chart']['customers'], $numberOfDaysBetweenDates, $currentDate);

        $data['chart']['orders'] = $this->getOrdersDataForDateRange($dateMonthAgo, $currentDate );
        $data['chart']['orders'] = $this->prepareDataForChartOrder( $data['chart']['orders'], $numberOfDaysBetweenDates, $currentDate );

        $this->renderView( $data );

    }

}