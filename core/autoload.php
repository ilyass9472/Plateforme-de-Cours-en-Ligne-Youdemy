<?php 
spl_autoload_register(function ($class) {

    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $fullPath = __DIR__ . '/../' . $path;

    if (file_exists($fullPath)) {
        require_once $fullPath;
    } else {
        die("Class file not found: " . $fullPath);
    }
});
