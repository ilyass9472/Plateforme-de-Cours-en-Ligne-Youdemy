<?php 
class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    // Afficher tous les utilisateurs
    public function index() {
        return $this->userModel->readAll();
    }

    // Ajouter un utilisateur
    public function store($name, $email, $role) {
        $this->userModel->create($name, $email, $role);
    }

    // Afficher un utilisateur spécifique
    public function show($id) {
        return $this->userModel->read($id);
    }

    // Mettre à jour un utilisateur
    public function update($id, $name, $email, $role) {
        $this->userModel->update($id, $name, $email, $role);
    }

    // Supprimer un utilisateur
    public function destroy($id) {
        $this->userModel->delete($id);
    }
}




?>