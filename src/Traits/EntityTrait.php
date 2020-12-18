<?php

namespace Dashboard\Traits;

trait EntityTrait {

    /**
     * Check if class has given attribute
     *
     * @param string $attr
     * @return bool
     */
    public function hasAttribute( $attr = "" ): bool {
        $objectVars = get_object_vars($this);
        return array_key_exists($attr, $objectVars);
    }

    /**
     * Map received data (should contain data for column in DB)
     * into class attributes (later is it used to build query)
     * @param $data
     */
    public function mapDataToEntityAttributes($data) {
        foreach( $data as $keyName => $value ) {
            if( $this->hasAttribute($keyName) ) {
                $this->$keyName = $value;
            }
        }
    }

    /**
     * Return parameters for bind_param, f.ex. ('ssiddss')
     * $doubleValue -> defines if above params will be doubled
     * used for UPDATE part of query
     *
     * @param bool $doubleValue
     * @return string
     */
    public function getTypesForBindParam( bool $doubleValue = true ): string {

        //Create dynamically types for bind_param()
        $types = "";
        foreach( self::$dbFields as $attr => $type ){
            $types .= $type;
        }

        // Double the value of types because of create/update statement in one
        if ( $doubleValue === true ) {
            $types .= $types;
        }

        return $types;

    }

    /**
     * Build question marks for prepared statement
     * based on class attributes (relating to columns in DB)
     *
     * @return string|string[]
     */
    public function getQuestionMarksForPreparedStatement() {
        $questionMarksValues = "(";

        $numberOfFields = count( self::$dbFields );
        for ( $i = 1; $i <= $numberOfFields; $i++ ) {

            if( $i === $numberOfFields ) {
                $questionMarksValues .= "?";
            } else {
                $questionMarksValues .= "? ";
            }

        }

        $questionMarksValues .= ")";

        return str_replace(' ', ',', $questionMarksValues);

    }

    /**
     * Return pairs for update query (f.ex. 'id' = ?, 'price' = ?)
     *
     * @param array $keysToBeExcluded
     * @return array
     */
    public function getPairsForUpdateQuery( $keysToBeExcluded = [] ) {

        $defaultKeysToBeExcluded = ['connection', 'logger'];
        $keysToBeExcluded = array_merge($defaultKeysToBeExcluded, $keysToBeExcluded);

        $attributes = get_object_vars( $this );

        $arrayPairs = [];

        foreach ( $attributes as $key => $value ) {

            if ( in_array($key, $keysToBeExcluded) !== false ) {
                continue;
            }

            $arrayPairs[] = "{$key} = ?";

        }

        return $arrayPairs;

    }

}