<?php
namespace App\Controllers;

use App\Models\Tag;
use Exception;

class TagController {
    private $tagModel;

    public function __construct() {
        $this->tagModel = new Tag();
    }
    
    public function createTag($name) {
        if (empty(trim($name))) {
            throw new Exception("Le nom du tag ne peut pas être vide");
        }
        return $this->tagModel->createTag($name);
    }
    
    public function getAllTags() {
        return $this->tagModel->getAllTags();
    }
    
    public function getTagById($id) {
        $tag = $this->tagModel->getTagById($id);
        if (!$tag) {
            throw new Exception("Tag non trouvé");
        }
        return $tag;
    }
    
    public function updateTag($id, $name) {
        if (empty(trim($name))) {
            throw new Exception("Le nom du tag ne peut pas être vide");
        }
        if (!is_numeric($id)) {
            throw new Exception("ID de tag invalide");
        }
        
        
        $existingTag = $this->getTagById($id);
        if (!$existingTag) {
            throw new Exception("Tag non trouvé");
        }
        
        return $this->tagModel->updateTag($id, trim($name));
    }
    
    public function deleteTag($id) {
        if (!is_numeric($id)) {
            throw new Exception("ID de tag invalide");
        }
        
        
        $existingTag = $this->getTagById($id);
        if (!$existingTag) {
            throw new Exception("Tag non trouvé");
        }
        
        return $this->tagModel->deleteTag($id);
    }
}