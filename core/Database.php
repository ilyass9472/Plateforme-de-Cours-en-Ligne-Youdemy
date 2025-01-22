<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;
    private function __construct() {
        $host = 'localhost';
        $db = 'youdemy';
        $user = 'root';
        $password = '';
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }


    public function query($sql, $params = [], $fetch = true) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $fetch ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->rowCount();
    }
}