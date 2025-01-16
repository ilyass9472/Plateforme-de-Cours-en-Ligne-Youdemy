<?php

namespace App\Models;

use Core\Database;
use PDOException;

class User {
    private $id;
    private $email;
    private $password;

    public static function findByEmail(string $email) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetchObject(self::class);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }

    public function updateStatus(string $status): bool {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "UPDATE users SET status = :status WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':email', $this->email);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new \Exception("Échec de la mise à jour du statut : " . $e->getMessage());
        }
    }

    
    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }
}
