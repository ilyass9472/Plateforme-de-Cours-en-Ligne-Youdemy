<?php
namespace App\Controllers;

use App\Models\User;

class UserController {
    private $model;

    public function __construct() {
        $this->model = new User();
    }

    public function index() {
        return $this->model->getAllUsers();
    }

    public function create($name, $email, $password, $role) {
        return $this->model->createUser($name, $email, $password, $role);
    }

    public function update($id, $name, $email, $password, $role) {
        return $this->model->updateUser($id, $name, $email, $password, $role);
    }

    public function delete($id) {
        return $this->model->deleteUser($id);
    }
}
