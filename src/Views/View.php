<?php

namespace Dashboard\Views;

use Dashboard\Models\CustomerEntity;
use Dashboard\Models\DB;

class View {

    public function renderView( string $controllerName, string $filename, array $data ): ?string {

        $filePath = $this->getFilePath($controllerName, $filename);


        if( file_exists( $filePath ) ) {

            ob_start();

            extract($data, EXTR_OVERWRITE);

            require( $filePath );

            return ob_get_clean();

        }

        return '';
    }

    private function getFilePath( string $controllerName, string $filename ): string {
        return TEMPLATES_PATH . $controllerName . '/' . $filename;
    }
}