<?php
namespace Core;

class Router {
    public static function execute($url) {
        $routes = require_once __DIR__ . '/../../config/routes.php';
        
        error_log('URL received: ' . $url);
        error_log('Available routes: ' . print_r($routes, true));
        
        if (isset($routes[$url])) {
            $parts = explode('@', $routes[$url]);
            $controllerName = "App\\Controllers\\" . $parts[0];
            $method = $parts[1];

            error_log('Controller: ' . $controllerName);
            error_log('Method: ' . $method);

            if (class_exists($controllerName)) {
                $controller = new $controllerName();

                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    die("Method $method not found in $controllerName");
                }
            } else {
                die("Class $controllerName not found");
            }
        } else {
            die("No route found for URL: $url");
        }
    }
}