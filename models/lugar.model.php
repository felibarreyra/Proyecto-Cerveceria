<?php
    require_once __DIR__ . '/../config/database.php';


class lugarModel {
    private $db;

    public function __construct() {
        // Crear una instancia de la clase Database para obtener la conexión
        $database = new Database();
        $this->db = $database->connect();  // Usar el método connect() para obtener la conexión PDO
    }

    public function getAllLugares() {
        $query = $this->db->prepare('SELECT * FROM lugar');
        $query->execute();
        $lugares = $query->fetchAll(PDO::FETCH_OBJ);
        
        // Verifica si la consulta devolvió datos
        if (empty($lugares)) {
            // Si no hay datos, se puede manejar el error o devolver un arreglo vacío
            return [];
        }
        
        return $lugares;
    }
    public function addLugar($nombre) {
        $query = $this->db->prepare('INSERT INTO lugar (nombre)VALUES (?)');
        return $query->execute([$nombre]);
    }
    public function obtenerNombrePorId($id_lugar) {
        $stmt = $this->db->prepare("SELECT nombre FROM lugar WHERE id_lugar = ?");
        $stmt->execute([$id_lugar]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['nombre'] : null;
    }
    public function agregarLugar($nombre) {
        $stmt = $this->db->prepare("INSERT INTO lugar (nombre) VALUES (UPPER(?))");
        $stmt->execute([$nombre]);
        // Verifica si la inserción fue exitosa
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function eliminarLugar($id) {
        $stmt = $this->db->prepare("DELETE FROM lugar WHERE id_lugar = ?");
        $stmt->execute([$id]);
    }
    
    
    
    


}