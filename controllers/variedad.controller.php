<?php
require_once __DIR__ . '/../models/variedad.model.php';

class variedadController {
    private $variedadModel;

    public function __construct() {
        $this->variedadModel = new Variedad();
    }

    public function getAllVariedades() {
        return $this->variedadModel->getAllVariedades();
    }

    public function addVariedad($nombre) {
        if (!empty($nombre)) {
            $this->variedadModel->addVariedad($nombre);
        } else {
            echo "Error: Todos los campos son obligatorios.";
        }
    }

    public function getNombreVariedad($idVariedad) {
        return $this->variedadModel->getNombreVariedad($idVariedad);
    }

    public function agregarVariedad($nombre, $precio) {
        return $this->variedadModel->agregarVariedad($nombre, $precio);
    }

    public function modificarPrecio($id_variedad, $precio) {
        return $this->variedadModel->modificarPrecio($id_variedad, $precio);
    }

    public function eliminarVariedad($id_variedad) {
        return $this->variedadModel->eliminarVariedad($id_variedad);
    }

    public function getPrecioPorLitro($id_variedad) {
        return $this->variedadModel->getPrecioPorLitro($id_variedad);
    }

}
