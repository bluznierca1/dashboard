<?php

namespace Dashboard\Mocker;

use Dashboard\Helpers\VariableHelper;
use Dashboard\Models\CustomerEntity;
use Dashboard\Models\OrderEntity;
use Dashboard\Models\OrderItemsEntity;

class Mocker {
    public function clearCustomersTable(): bool {
        $customersEntity = new CustomerEntity();
        return $customersEntity->eraseAllRecords();
    }

    public function clearOrdersTable(): bool {
        $orderEntity = new OrderEntity();
        return $orderEntity->eraseAllRecords();
    }

    public function clearOrderItemsTable(): bool {
        $orderItemsEntity = new OrderItemsEntity();
        return $orderItemsEntity->eraseAllRecords();
    }

    /**
     * Trigger function to clear all DB records
     * start erasing from end to avoid mess in db
     * f.ex. when customer is removed but his orders are still there
     * @return bool
     */
    public function clearDbRecords(): bool {

        if( $this->clearOrderItemsTable() ) {

            if ($this->clearOrdersTable()) {

                if ($this->clearCustomersTable()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function fillCustomersTable() {

        $content = file_get_contents('https://jsonplaceholder.typicode.com/users');

        if( empty($content) ) {
            return false;
        }

        $decodedContent = json_decode($content, true);

        if( empty($decodedContent) ) {
            return false;
        }

        $customersEntity = new CustomerEntity();

        $success = true;
        foreach( $decodedContent as $fakeCustomer ) {

            $foundCustomer = $customersEntity->findByEmail($fakeCustomer['email']);

            $explodedName = explode(' ', $fakeCustomer['name']);
            $customerData = [
                'id' => $foundCustomer['id'] ?? null,
                'first_name' => $explodedName[0],
                'last_name' => $explodedName[1],
                'email' => $fakeCustomer['email']
            ];

            if( !$customersEntity->save($customerData) ) {
                echo $fakeCustomer['name'] . ' FAILED while saving. <br />';
                $success = false;
            }

        }

        return $success;

    }

    public function fillOrdersTable(): bool {

        $customerEntity = new CustomerEntity();
        $allCustomers = $customerEntity->getAll();

        if( empty($allCustomers) ) {
            return false;
        }

        $success = true;
        foreach( $allCustomers as $customer ) {

            $numberOfOrders = VariableHelper::getRandomIntegerFromRange(1, 50);

            for ($i = 0; $i < $numberOfOrders; $i++) {
                // make random date from 3 yars ago until now
                $randomTimestamp = VariableHelper::getRandomIntegerFromRange(strtotime('-2 years'), time());

                $countries = [
                    'Denmark',
                    'Djibouti',
                    'Dominica',
                    'Dominican Republic',
                    'Gabon',
                    'The Gambia',
                    'Georgia',
                    'Germany',
                    'Ghana',
                    'Greece',
                    'Grenada',
                    'Guatemala',
                    'Guinea',
                    'Guinea-Bissau',
                    'Guyana',
                    'Pakistan',
                    'Palau',
                    'Panama',
                    'Papua New Guinea',
                    'Paraguay',
                    'Peru',
                    'Philippines',
                    'Poland',
                    'Portugal',
                ];
                $randomCountry = $countries[VariableHelper::getRandomIntegerFromRange(0, count($countries) - 1)];

                $devices = [
                    'mobile',
                    'TV',
                    'fridge',
                    'tablet',
                    'laptop'
                ];
                $randomDevice = $devices[VariableHelper::getRandomIntegerFromRange(0, count($devices) - 1)];

                $data = [
                    'purchase_date' => date('Y-m-d H:i:s', $randomTimestamp),
                    'country'       => $randomCountry,
                    'device'        => $randomDevice,
                    'customer_id'   => $customer['id']
                ];

                $orderEntity = new OrderEntity();
                if( !$orderEntity->save($data) ) {
                    echo  'Saving order FAILED <br />';
                    $success = false;
                }

            }

        }

        return $success;

    }

    public function fillOrderItemsTable() {

        $ordersEntity = new OrderEntity();
        $allOrders = $ordersEntity->getAll();

        if( empty($allOrders) ) {
            return false;
        }

        $orderItemsEntity = new OrderItemsEntity();

        $success = true;
        foreach( $allOrders as $order ) {
            $numberOfProductsForOrder = VariableHelper::getRandomIntegerFromRange(1, 20); // random number of products assigned to order

            for( $i = 0; $i < $numberOfProductsForOrder; $i++ ) {

                $data = [
                    'ean'       => (string) VariableHelper::getRandomIntegerFromRange(10000000, 99999999), // 8 or 13 digits. Go for 8
                    'quantity'  => VariableHelper::getRandomIntegerFromRange(1, 100),
                    'price'     => VariableHelper::getRandomIntegerFromRange(1, 10000) / 100,
                    'order_id'  => $order['id']
                ];

                if( !$orderItemsEntity->save($data) ) {
                    echo 'FAILURE while creating record for order: ' . $order['id'] . '<br />';
                    $success = false;
                }

            }

        }

        return $success;

    }

    public function fillDbWithRecords(): bool {

        if ($this->fillCustomersTable()) {

            if ($this->fillOrdersTable()) {

                if ($this->fillOrderItemsTable()) {
                    return true;
                }

            }

        }

        return false;
    }
}