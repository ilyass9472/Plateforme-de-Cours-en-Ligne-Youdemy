<?php
namespace App\Controllers;

use App\Models\Tag;

class TagController {
    private $model;

    public function __construct() {
        $this->model = new Tag();
    }

    public function index() {
        return $this->model->getAllTags();
    }

    public function create($name) {
        return $this->model->createTag($name);
    }

    public function update($id, $name) {
        return $this->model->updateTag($id, $name);
    }

    public function delete($id) {
        return $this->model->deleteTag($id);
    }
}
