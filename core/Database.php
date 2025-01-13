<?php

namespace Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;
    private $host = 'localhost';
    private $db = 'youdemy';
    private $user = 'root';
    private $password = '';



    private function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
    }

    
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
