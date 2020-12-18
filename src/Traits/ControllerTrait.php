<?php

namespace Dashboard\Traits;

use Dashboard\Models\CustomerEntity;
use Dashboard\Models\OrderEntity;
use Dashboard\Models\OrderItemsEntity;

trait ControllerTrait {

    /**
     * @param string $currentDate
     * @return array
     */
    public function getCustomersDataUntilDate(string $currentDate = ''): array {
        $customersEntity = new CustomerEntity();
        return $customersEntity->getCustomersUntilGivenDate($currentDate);
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @return array
     */
    public function getOrdersDataForDateRange( string $dateFrom = '', string $dateTo = '' ): array {
        $ordersEntity = new OrderEntity();
        return $ordersEntity->fetchDataForDateRange($dateFrom, $dateTo);
    }

    /**
     * SKIP building data based on customers who deleted accounts
     * build customers data for chart
     *
     * @param array $customersData
     * @param int $numberOfDaysBetweenDates
     * @param string $dateTo
     * @return array
     */
    public function prepareDataForChartCustomers( array $customersData = [], int $numberOfDaysBetweenDates = 1, string $dateTo = '' ): array {
        $customersForChart = [];

        // number of all customers until given $dateTo - will get decreased
        // by customers who got created in the range of dates
        $customersForChart['existingCustomers'] = $customersForChart['customersTotal'] = count($customersData);

        for(; $numberOfDaysBetweenDates >= 0; $numberOfDaysBetweenDates-- ) {

            $extracted = date('Y-m-d', strtotime('-' . $numberOfDaysBetweenDates . 'day', strtotime($dateTo)));

            $customersForChart['customersByDays'][$numberOfDaysBetweenDates] = $customersForChart['existingCustomers'];
            foreach( $customersData as $customerData ) {
                $customerDateCreated = date('Y-m-d', strtotime($customerData['date_created']));

                if( $extracted === $customerDateCreated ) {

                    $customersForChart['customersByDays'][$numberOfDaysBetweenDates]--; // new customers per day
                    // decrease total number which will be set on first day
                    // to leave only customers registered before dateFrom
                    $customersForChart['existingCustomers']--;
                }
            }

        }

        // Since we start from highest index, reverse it
        $customersForChart['customersByDays'] = !empty($customersForChart['customersByDays']) ? array_reverse($customersForChart['customersByDays']) : [];

        return $customersForChart;

    }

    /**
     * Receive total revenue and number of items assigned to order
     *
     * @param int|null $orderId
     * @return array
     */
    public function getTotalPriceAndNumberOfItemsForOrder( int $orderId = null): array {

        if( !isset($orderId) ) {
            return [];
        }

        $orderItemsEntity = new OrderItemsEntity();

        return $orderItemsEntity->getTotalItemsAndRevenueForOrder($orderId);
    }

    /**
     * Get number of orders received for a specific day
     * Get all items assigned to order and calculate total revenue
     *
     * @param array $ordersData
     * @param int $numberOfDaysBetweenDates
     * @param string $dateTo
     * @return array
     */
    public function prepareDataForChartOrder( array $ordersData = [], int $numberOfDaysBetweenDates = 1, string $dateTo = '' ): array {

        $filled = [];
        $filled['ordersTotal'] = 0;
        $filled['ordersRevenue'] = 0;

        // Loop number of days that we need for chart
        for($i = 0;  $i < $numberOfDaysBetweenDates; $i++ ) {

            // Initially number of orders for particular day is 0
            $filled['ordersByDays'][$i] = 0;

            // $dateTo - $i: check each day from $dateTo until $dateFrom
            $extracted = date('Y-m-d', strtotime('-' . $i . 'day', strtotime($dateTo)));

            foreach( $ordersData as $index => $orderData ) {
                $orderPurchaseDate = date('Y-m-d', strtotime($orderData['purchase_date']));

                // If dates match, increment and get order data
                if( $extracted === $orderPurchaseDate ) {
                    $filled['ordersByDays'][$i]++;
                    $filled['ordersTotal']++;

                    // Get all items assigned to order
                    $totalOrder = $this->getTotalPriceAndNumberOfItemsForOrder($orderData['id']);

                    // total_revenue is price * entity for all items
                    $filled['ordersRevenue'] += $totalOrder['total_revenue'] ?? 0;

                }

            }

        }

        // $extracted takes days from the end so we need to reverse array to show data correctly
        $filled['ordersByDays'] = !empty($filled['ordersByDays']) ? array_reverse($filled['ordersByDays']) : [];

        // display total revenue in nice format
        $filled['ordersRevenue'] = number_format($filled['ordersRevenue'], 2, ',', '.');

        return $filled;
    }

}