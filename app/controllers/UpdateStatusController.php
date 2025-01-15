<?php

namespace App\Controllers;

use App\Models\User;

class UpdateStatusController {
    public static function updateStatus() {
        $email = $_POST['email'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($email && $status) {
            $user = new User();
            $user->updateStatus($email, $status);
            $_SESSION['message'] = 'Statut mis à jour avec succès !';
        } else {
            $_SESSION['message'] = 'Données invalides fournies.';
        }

        header('Location: /public/index.php');
        exit;
    }
}
