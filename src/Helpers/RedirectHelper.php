<?php

namespace Dashboard\Helpers;

class RedirectHelper {

    public static function redirectToHomePage(): void {
        header('Location: ' . HOME_URL);
    }

}