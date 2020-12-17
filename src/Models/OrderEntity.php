<?php

namespace Dashboard\Models;

use Dashboard\Interfaces\EntityInterface;
use Dashboard\Traits\EntityTrait;
use Exception;

class OrderEntity extends DB implements EntityInterface {

    use EntityTrait;

    public $id;
    public $purchase_date;
    public $country;
    public $device;
    public $customer_id;

    // get_object_vars() is used to build query - exclude unwanted parameters
    protected static $keysToBeExcludedFromQuery = ['connection'];

    protected static $tableName = 'orders';
    protected static $dbFields = [
        'id'            => 'i',
        'purchase_date' => 's',
        'country'       => 's',
        'device'        => 's',
        'customer_id'   => 'i'
    ];


    public function getAll(): ?array {
        $query = 'SELECT * FROM ' . self::$tableName;
        return $this->executeGetRowsQuery($query);
    }

    public function findById( int $id = null ) {
        // TODO: Implement findById() method.
    }

    public function save( array $data = [] ): ?int {

        $this->mapDataToEntityAttributes($data);

        $query = "INSERT INTO " . self::$tableName . " (";
        $query .= implode(', ', array_keys( self::$dbFields) );
        $query .= ") VALUES ";
        $query .= $this->getQuestionMarksForPreparedStatement();

        try {
            $stmt = $this->connection->prepare($query);

            if( $stmt ) {

                $stmt->bind_param(
                    $this->getTypesForBindParam(false),
                    $this->id,
                    $this->purchase_date,
                    $this->country,
                    $this->device,
                    $this->customer_id
                );

                if( $stmt->execute() ) {
                    $id = $stmt->id ?? null;
                    $stmt->close();
                    return $id;
                }

            }

            throw new Exception( $this->connection->error );
        } catch( Exception $e ) {
            echo 'ERROR: ' . $e->getMessage();
            return null;
        }

    }

    public function eraseAllRecords(): bool {
        return $this->deleteAll(self::$tableName);
    }

}