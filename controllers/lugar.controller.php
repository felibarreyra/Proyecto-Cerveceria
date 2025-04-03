<?php
require_once './models/lugar.model.php';

class lugarController {
    private $lugarModel;

    public function __construct() {
        $this->lugarModel = new lugarModel();
    }

    public function getAllLugares() {
        $lugares = $this->lugarModel->getAllLugares();
        return $lugares;  
    }
    
    public function addLugar($nombre){
        if (!empty($nombre)) {
            $this->lugarModel->addLugar($nombre);
        } else {
            echo "Error: Todos los campos son obligatorios.";
            return;
        }
    }
    public function getNombreLugarById($id_lugar) {
        return $this->lugarModel->obtenerNombrePorId($id_lugar);
    }
    
}

