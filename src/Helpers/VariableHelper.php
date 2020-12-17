<?php

namespace Dashboard\Helpers;

class VariableHelper {

    /**
     * Prepare path which are used as array key
     * @param $path
     * @return string|string[]
     */
    public static function prepareRequestUriPathForRouter( $path ) {

        if (is_array($path)) {
            $path = implode('_', $path);
        }

        if(is_string($path)) {
            $path = str_replace('/', '_', $path);
        }

        return self::removePhpExtensionFromString($path);

    }

    /**
     * Remove .php from string (so both 'index' and 'index.php' will be accepted)
     *
     * @param $string
     * @return string|string[]
     */
    public static function removePhpExtensionFromString( $string ) {
        return str_replace('.php', '', $string);
    }

    /**
     * Return random number from given range
     *
     * @param int $from
     * @param int $to
     * @return int
     * @throws \Exception
     */
    public static function getRandomIntegerFromRange( int $from = 0, int $to = 9999999): int {
        return $to > $from ? random_int($from, $to) : random_int($to, $from);
    }
}