<?php
require_once __DIR__ . '/../models/barril.model.php';
require_once __DIR__ . '/../models/lugar.model.php';

class BarrilController {
    private $barrilModel;
    private $lugarModel;

    public function __construct() {
        $this->barrilModel = new Barril();
        $this->lugarModel = new lugarModel();
    }

    public function insertarBarril() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
            $id_variedad = 4;
            $id_lugar = 4;
            $litros = filter_input(INPUT_POST, 'litros', FILTER_VALIDATE_INT);
            $estado = 'VACIO';

           // Verificar si algún dato es inválido o está vacío
        if (!$codigo || !$id_variedad || !$id_lugar || !$litros || !$estado) {
            header("Location: ./listar_barriles_vacios.php?mensaje=dato_incompleto");
         exit();
            }

            // Intentar insertar el barril en la base de datos
        if ($this->barrilModel->insertarBarril($codigo, $id_variedad, $id_lugar, $litros, $estado)) {
        header("Location: ./listar_barriles_vacios.php?mensaje=exito");
        } else {
        header("Location: ./listar_barriles_vacios.php?mensaje=error");
        }
        exit();

        }
    }

    public function getBarriles() {
        return $this->barrilModel->obtenerTodos();
    }

    public function mostrarFormulario() {
        $variedades = $this->barrilModel->getAllVariedades();
        $lugares = $this->lugarModel->getAllLugares();
        require_once('./views/formagregar.php');
    }

    public function getBarrilesFiltradosVentas($variedad = '', $codigo = '', $litros = '',$lugar) {
        return $this->barrilModel->getBarrilesFiltradosVentas($variedad, $codigo, $litros,$lugar);
    }

    public function getBarrilesFiltradosCamara($variedad = '', $codigo = '', $litros = '') {
        return $this->barrilModel->getBarrilesFiltradosCamara($variedad, $codigo, $litros);
    }

    public function getEstadoByCodigo($codigo) {
        return $this->barrilModel->getEstadoByCodigo($codigo) ?? null;
    }

    
    public function modificarBarril() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
            $id_variedad = filter_input(INPUT_POST, 'id_variedad', FILTER_VALIDATE_INT);
            $id_lugar = filter_input(INPUT_POST, 'id_lugar', FILTER_VALIDATE_INT);
            $litros = filter_input(INPUT_POST, 'litros', FILTER_VALIDATE_INT);
            $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $fecha_venta = date("Y-m-d H:i:s");
    
            $result = $this->barrilModel->modificarBarrilPorCodigo($codigo, $id_variedad, $id_lugar, $litros, $estado, $fecha_venta);
            
            if ($result) {
                // Redirigir siempre a actualizarbarril.php después de modificar
                header("Location: ./actualizarbarril.php?mensaje=exito");
                exit();
            } else {
                // Si hubo un error en la actualización, redirigir con mensaje de error
                header("Location: ./actualizarbarril.php?mensaje=error");
                exit();
            }
        }
    }
    
    public function cambiarLugarBarril($id_barril, $id_lugar) {

        return $this->barrilModel->actualizarLugarBarril($id_barril, $id_lugar);
    }
    
    
    public function getBarrilesPorEstado($estado) {
        return $this->barrilModel->obtenerBarrilesPorEstado($estado);
    }
    public function getBarrilesVacios($estado, $litros = null){
        return $this->barrilModel->getBarrilesFiltro($estado, $litros);
    }
    public function getBarrilById($id_barril) {
        return $this->barrilModel->obtenerBarrilPorId($id_barril);
    }

    public function getBarrilBycodigo($codigo){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_barril'])) {
        // Verificamos que el código esté presente en el POST
        if (isset($_POST['codigo']) && !empty($_POST['codigo'])) {
            // Obtener el código del barril desde el formulario
            $codigo = $_POST['codigo'];
            // Llamar al método para obtener el barril por código
            $resultado =$this->barrilModel->obtenerBarrilPorCodigo($codigo);
            return $resultado;
        }
    }
    }
    public function getFechaByCodigo($codigo) {
        return $this->barrilModel->getFechaByCodigo($codigo);
    }

   // Controller: getNombre
public function getNombre($id_lugar) {
    // Llamamos a la función que obtiene el nombre del lugar
    return $this->barrilModel->obtenerNombreLugar($id_lugar);
}


public function getBarrilesPorClienteOFecha($cliente, $fechaInicio, $fechaFin) {
    return $this->barrilModel->getBarrilesPorClienteOFecha($cliente, $fechaInicio, $fechaFin);
}


    public function getVariedadPorId($id_variedad){
        return $this->barrilModel->getVariedadPorId($id_variedad);
    }
    public function obtenerBarrilesEnCamaraActuales() {
        return $this->barrilModel->obtenerBarrilesEnCamaraActuales();
    }
    
    
}
