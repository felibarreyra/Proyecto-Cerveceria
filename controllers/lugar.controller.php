<?php
require_once __DIR__ . '/../models/lugar.model.php';

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
    
    public function agregarLugar($nombre) {
        return $this->lugarModel->agregarLugar($nombre);
    }
    public function eliminarLugar($id) {
        $this->lugarModel->eliminarLugar($id);
    }
    public function getVentasPorClienteYFecha($id_lugar, $fecha) {
        return $this->model->getVentasPorClienteYFecha($id_lugar, $fecha);
    }
    
    
}

