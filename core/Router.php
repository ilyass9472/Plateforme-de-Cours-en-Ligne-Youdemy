<?php

namespace App\Core;

class Router {
    private static $routes = [];

    public static function route($url, $callback) {
        self::$routes[$url] = $callback;
    }

    public static function execute($url) {
        if (isset(self::$routes[$url])) {
            call_user_func(self::$routes[$url]);
        } else {
            die("Route non trouvée : $url");
        }
    }
}
