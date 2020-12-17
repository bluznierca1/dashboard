<?php

namespace Dashboard\Models;

use Dashboard\Interfaces\EntityInterface;
use Dashboard\Traits\EntityTrait;
use Exception;

class OrderItemsEntity extends DB implements EntityInterface {

    use EntityTrait;

    public $id;
    public $ean;
    public $quantity;
    public $price;
    public $order_id;

    // get_object_vars() is used to build query - exclude unwanted parameters
    protected static $keysToBeExcludedFromQuery = ['connection'];

    protected static $tableName = 'order_items';
    protected static $dbFields = [
        'id'        => 'i',
        'ean'       => 's',
        'quantity'  => 'i',
        'price'     => 'd',
        'order_id'  => 'i'
    ];

    /**
     * @param array $data
     * @return int|null
     */
    public function save(array $data = []): ?int {

        $this->mapDataToEntityAttributes($data);

        $query = "INSERT INTO " . self::$tableName . " (";
        $query .= implode(', ', array_keys( self::$dbFields) );
        $query .= ") VALUES ";
        $query .= $this->getQuestionMarksForPreparedStatement();

        try {
            if( $stmt = $this->connection->prepare($query) ) {

                $stmt->bind_param(
                    $this->getTypesForBindParam(false),
                    $this->id,
                    $this->ean,
                    $this->quantity,
                    $this->price,
                    $this->order_id
                );

                if( $stmt->execute() ) {

                    $id = $stmt->id ?? null;
                    $stmt->close();
                    return $id;

                }

            }

            throw new Exception( $this->connection->error );

        } catch( Exception $e ) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function findById(int $id = null) {
        // TODO: Implement findById() method.
    }

    /**
     * @return array|null
     */
    public function getAll(): ?array {
        $query = 'SELECT * FROM ' . self::$tableName;
        return $this->executeGetRowsQuery($query);
    }

    /**
     * @return bool
     */
    public function eraseAllRecords(): bool {
        return $this->deleteAll(self::$tableName);
    }

}