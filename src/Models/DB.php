<?php

namespace Dashboard\Models;

use Exception;

abstract class DB {

    protected $connection;

    public function __construct() {
        // Set connection object to class attribute so we can get it in every child class
        $this->connection = $this->createDbConnection();
    }

    /**
     * Attempt to create DB connection
     * Die if failure
     *
     * @return object
     */
    private function createDbConnection(): object {

        $ini_array = parse_ini_file(APP_ROOT . "/config.ini", true)['database_configuration'];

        $connection = @mysqli_connect($ini_array['database_host'], $ini_array['database_user'], $ini_array['database_password'], $ini_array['database_name']);

        // Test if connection succeeded
        if(mysqli_connect_errno()) {
            die("Database connection failed: " .
                mysqli_connect_error() .
                " (" . mysqli_connect_errno() . ")"
            );
        }

        return $connection;
    }

    /**
     * @param string $query
     * @return array
     */
    protected function executeGetRowsQuery( string $query = '' ): array {

        try {

            if( $stmt = $this->connection->prepare($query) ) {

                if( $stmt->execute() ) {
                    $result = $stmt->get_result();

                    $rows = $this->getAllRowsFromResultAsAssocArray($result);

                    $stmt->close();

                    return $rows;

                }

            }

            throw new Exception( $this->connection->error );

        } catch( \Exception $e ) {
            die($e->getMessage());
        }

    }

    /**
     * Delete all records from given table
     * Table needs to contain 'id' column
     *
     * @param string $tableName
     * @return bool
     */
    protected function deleteAll( string $tableName = '' ): bool {

        try {

            if( empty($tableName) ) {
                throw new Exception('Table name is missing in: ' . __METHOD__);
            }

            $query = 'DELETE FROM ' . $tableName . ' WHERE id > 0';

            if( $stmt = $this->connection->prepare($query) ) {

                if( $stmt->execute() ) {

                    $stmt->close();
                    return true;
                }
            }

            throw new Exception( $this->connection->error );

        } catch( Exception $e ) {
            die('ERROR: ' . $e->getMessage());
        }

    }

    /**
     * Fetch associative array from result
     *
     * @param $result
     * @return array
     */
    public function getAllRowsFromResultAsAssocArray( $result ): array {
        $rows = [];

        while( $row = $result->fetch_assoc() ) {
            $rows[] = $row;
        }

        return $rows;
    }

}