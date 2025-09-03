<?php
require_once __DIR__ . '/../models/lata.model.php';

class LataController {
    private $model;

    public function __construct() {
        $this->model = new LataModel();
    }

    public function listarLatas() {
        return $this->model->getAllLatas();
    }

    public function cargarLatas($id_variedad, $cantidad) {
        return $this->model->addOrUpdate($id_variedad, $cantidad);
    }
    public function descontarStock($id_variedad, $cantidad) {
        return $this->model->descontarStock($id_variedad, $cantidad);
    }
    public function registrarVenta($id_variedad, $id_lugar, $cantidad, $precio_unitario) {
        return $this->model->registrarVenta($id_variedad, $id_lugar, $cantidad, $precio_unitario);
    }
    public function getByVariedad($id_variedad) {
        return $this->model->getByVariedad($id_variedad);
    }
    public function getVentasPorClienteYFecha($id_lugar, $fecha) {
        return $this->model->getVentasPorClienteYFecha($id_lugar, $fecha);
    }
}
