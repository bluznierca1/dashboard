<?php

namespace Dashboard\Views;

use Dashboard\Models\CustomerEntity;
use Dashboard\Models\DB;

class View {

    /**
     * Get proper template, extract data so it is available in there
     * and return as string so controller can handle rest
     *
     * @param string $controllerName
     * @param string $filename
     * @param array $data
     * @return string|null
     */
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

    /**
     * Build file path based on filename and controller name
     * @param string $controllerName
     * @param string $filename
     * @return string
     */
    private function getFilePath( string $controllerName, string $filename ): string {
        return TEMPLATES_PATH . $controllerName . '/' . $filename;
    }
}