<?php

namespace Dashboard\Helpers;

class VariableHelper {

    public static function prepareRequestUriPathForRouter( $path ) {

        if (is_array($path)) {
            $path = implode('_', $path);
        }

        if(is_string($path)) {
            $path = str_replace('/', '_', $path);
        }

        return self::removePhpExtensionFromString($path);

    }

    public static function removePhpExtensionFromString( $string ) {
        return str_replace('.php', '', $string);
    }

    public static function getRandomIntegerFromRange( int $from = 0, int $to = 9999999): int {
        return $to > $from ? random_int($from, $to) : random_int($to, $from);
    }
}