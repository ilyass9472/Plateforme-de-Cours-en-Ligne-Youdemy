<?php

namespace App\Controllers;

use App\Models\Enseignant;

class EnseignantController
{
    private $enseignantModel;

    public function __construct()
    {
        $this->enseignantModel = new Enseignant();
    }

    
    public function createEnseignant($data)
    {
        return $this->enseignantModel->create($data);
    }

    
    public function listEnseignants()
    {
        return $this->enseignantModel->getAll();
    }

    
    public function getEnseignant($id)
    {
        return $this->enseignantModel->getById($id);
    }

    
    public function updateEnseignant($id, $data)
    {
        return $this->enseignantModel->update($id, $data);
    }
    
    public function deleteEnseignant($id)
    {
        return $this->enseignantModel->delete($id);
    }
}
