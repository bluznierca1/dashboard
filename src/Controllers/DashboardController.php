<?php

namespace Dashboard\Controllers;

use Dashboard\Interfaces\ControllerInterface;
use Dashboard\Models\CustomerEntity;
use Dashboard\Models\OrderEntity;
use Dashboard\Models\OrderItemsEntity;

class DashboardController extends Controller {

    public function index() {


        $currentDate = date('Y-m-d');
        $dateMonthAgo = date('Y-m-d', strtotime('-1 month'));

        $data['pageTitle'] = 'Hello World';
        $data['dateNow'] = $currentDate;
        $data['dateMonthAgo'] = $dateMonthAgo;

        $numberOfDaysBetweenDates = round((strtotime($currentDate) - strtotime($dateMonthAgo)) / (60 * 60 * 24));

        // On init fetch only last month
        $data['chart']['customers'] = $this->getCustomersDataForLastMonth($currentDate, $dateMonthAgo);
        $data['chart']['orders'] = $this->getOrdersDataForLastMonth($currentDate, $dateMonthAgo);


        $data['chart']['orders'] = $this->prepareDataForChartOrder( $data['chart']['orders'], $numberOfDaysBetweenDates, $currentDate );

//        $data['chart']['customers'] = $this->prepareDataForChartCustomers( $data['chart'], )

        echo '<pre>';
        print_r($data);
        die();

        $this->renderView( $data );
    }

    // Get number of orders received for a specific day
    public function prepareDataForChartOrder( array $ordersData = [], int $numberOfDaysBetweenDates = 1, string $dateTo = '' ): array {

        $filled = [];
        $filled['ordersTotal'] = 0;
        $filled['ordersRevenue'] = 0;

        // Loop number of days that we need for chart
        for($i = 0;  $i < $numberOfDaysBetweenDates; $i++ ) {

            // Set index to 0 because
            $filled['ordersByDays'][$i] = null;

            // $dateTo - $i: check each day from $dateTo until $dateFrom
            $extracted = date('Y-m-d', strtotime('-' . $i . 'day', strtotime($dateTo)));

            foreach( $ordersData as $index => $orderData ) {
                $orderPurchaseDate = date('Y-m-d', strtotime($orderData['purchase_date']));

                // If dates match, increment and get order data
                if( $extracted === $orderPurchaseDate ) {
                    $filled['ordersByDays'][$i]++;
                    $filled['ordersTotal']++;

                    // Get all items assigned to order
                    $totalOrder = $this->getTotalPriceAndNumberOfItemsForOrder( $orderData['id']);

                    // total_revenue is price * entity for all items
                    $filled['ordersRevenue'] += $totalOrder['total_revenue'] ?? 0;

                }

            }

        }

        return $filled;
    }

    public function getTotalPriceAndNumberOfItemsForOrder( int $orderId = null): array {

        if( !isset($orderId) ) {
            return [];
        }

        $orderItemsEntity = new OrderItemsEntity();

        return $orderItemsEntity->getTotalItemsAndRevenueForOrder($orderId);
    }

    public function getCustomersDataForLastMonth(string $currentDate = '', string $dateMonthAgo = '') {
        $customersEntity = new CustomerEntity();

        return $customersEntity->fetchDataForDateRange($dateMonthAgo, $currentDate);
    }

    public function getOrdersDataForLastMonth(string $currentDate = '', string $dateMonthAgo = '' ) {
        $ordersEntity = new OrderEntity();

        return $ordersEntity->fetchDataForDateRange($dateMonthAgo, $currentDate);


    }

}