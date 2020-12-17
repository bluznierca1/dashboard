<?php

namespace Dashboard\Traits;

trait EntityTrait {

    public function hasAttribute( $attr = "" ): bool {
        $objectVars = get_object_vars($this);
        return array_key_exists($attr, $objectVars);
    }

    public function mapDataToEntityAttributes($data) {
        foreach( $data as $keyName => $value ) {
            if( $this->hasAttribute($keyName) ) {
                $this->$keyName = $value;
            }
        }
    }

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