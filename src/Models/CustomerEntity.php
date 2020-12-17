<?php

namespace Dashboard\Models;

use Dashboard\Interfaces\EntityInterface;
use Dashboard\Traits\EntityTrait;
use Exception;

class CustomerEntity extends DB implements EntityInterface {

    use EntityTrait;

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $date_created;

    // get_object_vars() is used to build query - exclude unwanted parameters
    protected static $keysToBeExcludedFromQuery = ['connection'];

    protected static $tableName = 'customers';
    protected static $dbFields = [
        'id'            => 'i',
        'first_name'    => 's',
        'last_name'     => 's',
        'email'         => 's',
        'date_created'  => 's'
    ];

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

    /**
     * @param int|null $id
     * @return array
     */
    public function findById( int $id = null ): array {

        if( !isset($id) ) {
            return [];
        }

        try {
            $query = 'SELECT * FROM ' . self::$tableName . ' WHERE id = ?';

            $stmt = $this->connection->prepare($query);
            if( $stmt ) {
                $stmt->bind_param(
                    'i',
                    $id
                );

                if( $stmt->execute() ) {
                    $result = $stmt->get_result()->fetch_assoc();
                    $stmt->close();
                    return $result;
                }
            }

            throw new Exception( $this->connection->error );

        } catch( Exception $e ) {
            die('ERROR: ' . $e->getMessage());
        }

    }

    /**
     * Attempt to find by email - unique
     *
     * @param string $email
     * @return mixed
     */
    public function findByEmail( string $email = '' ): ?array {

        try {
            $query = "SELECT * FROM " . self::$tableName . " WHERE email = ?";

            $stmt = $this->connection->prepare($query);
            if ($stmt) {
                $stmt->bind_param(
                    's',
                    $email
                );

                if ($stmt->execute()) {
                    $result = $stmt->get_result()->fetch_assoc();
                    $stmt->close();
                    return $result;
                }

            }

            throw new Exception( $this->connection->error );

        } catch( Exception $e ) {
            die($e->getMessage());
        }


    }

    public function save( array $data = [] ): ?int {

        // Map received data to entity attributes
        $this->mapDataToEntityAttributes($data);

        $query = "INSERT INTO " . self::$tableName . " (";
        $query .= implode(', ', array_keys( self::$dbFields) );
        $query .= ") VALUES ";
        $query .= $this->getQuestionMarksForPreparedStatement();
        $query .= " ON DUPLICATE KEY UPDATE ";
        $query .= implode(', ', $this->getPairsForUpdateQuery(self::$keysToBeExcludedFromQuery) );

        try {

            $stmt = $this->connection->prepare($query);
            if( $stmt ) {

                $stmt->bind_param(
                    $this->getTypesForBindParam(true),
                    $this->id,
                    $this->first_name,
                    $this->last_name,
                    $this->email,
                    $this->date_created,
                    // ON UPDATE
                    $this->id,
                    $this->first_name,
                    $this->last_name,
                    $this->email,
                    $this->date_created
                );

                if( $stmt->execute() ) {
                    $id = $stmt->id ?? null;
                    $stmt->close();
                    return $id;
                }

            }

            throw new Exception( $this->connection->error );

        } catch( Exception $e ) {
            echo 'EXCEPTION ERROR: ' . $e->getMessage();
            return null;
        }

    }

    public function fetchDataForDateRange( string $dateFrom = '', string $dateTo = '' ): ?array {

        $query = 'SELECT * FROM ' . self::$tableName . ' WHERE date_created BETWEEN ? AND ?';

        try {

            if( $stmt = $this->connection->prepare($query) ) {

                $stmt->bind_param(
                    'ss',
                    $dateFrom,
                    $dateTo
                );

                if( $stmt->execute() ) {

                    $result = $stmt->get_result();

                    $customers = $this->getAllRowsFromResultAsAssocArray($result);
                    $stmt->close();

                    return $customers;
                }
            }

            throw new Exception( $this->connection->error );

        } catch( Exception $e ) {
            die($e->getMessage());
        }

    }

}