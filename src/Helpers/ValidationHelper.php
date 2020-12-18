<?php

namespace Dashboard\Helpers;

class ValidationHelper {

    /**
     * Check if given number is between $min and $max
     *
     * @param int $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function isNumberBetween( int $value = -1, int $min = 0, int $max = 99 ): bool {
        return ( $value >= $min && $value <= $max );
    }

    /**
     * Check if given value is date: YYYY-mm-dd
     *
     * @param string $value
     * @return bool
     */
    public static function isValueCorrectDateFormat( string $value = '' ): bool {

        // correct date format we support here: YYYY-mm-dd
        if( (int) substr_count($value, '-') === 2 ) {

            $explodedValue = explode('-', $value);

            if( strlen($explodedValue[0]) === 4 && strlen($explodedValue[1]) === 2 && strlen($explodedValue[2]) === 2 ) {

                if( is_numeric($explodedValue[0]) && is_numeric($explodedValue[1]) && is_numeric(2) ) {

                    if( self::isNumberBetween($explodedValue[0], 1971, 2050) &&
                        self::isNumberBetween($explodedValue[1], 1, 12) &&
                        self::isNumberBetween($explodedValue[2], 1, 31)
                    ) {
                        return true;
                    }
                }

            }

        }

        return false;
    }

    /**
     * Check if expected keys are included in array
     *
     * @param array $expectedFields
     * @param array $receivedArray
     * @return bool
     */
    public static function areExpectedFieldsInArray( array $expectedFields = [], array $receivedArray = [] ): bool {

        $isProvidedData = true;
        foreach( $expectedFields as $expectedField ) {
            if( !isset($receivedArray[$expectedField]) ) {
                $isProvidedData = false;
                break;
            }
        }

        return $isProvidedData;

    }

}