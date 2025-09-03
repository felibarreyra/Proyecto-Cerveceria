<?php
require_once __DIR__ . '/../config/database.php';


class Variedad {
    private $db;

    public function __construct() {
        // Crear una instancia de la clase Database para obtener la conexión
        $database = new Database();
        $this->db = $database->connect();  // Usar el método connect() para obtener la conexión PDO
    }

    public function getAllVariedades() {
        $query = $this->db->prepare('SELECT * FROM variedades');
        $query->execute();
        $variedades = $query->fetchAll(PDO::FETCH_OBJ);
        
        // Verifica si la consulta devolvió datos
        if (empty($variedades)) {
            // Si no hay datos, se puede manejar el error o devolver un arreglo vacío
            return [];
        }
        
        return $variedades;
    }
    
    public function addVariedad($nombre) {
        $query = $this->db->prepare('INSERT INTO variedades (nombre)VALUES (?)');
        return $query->execute([$nombre]);
    }
    public function getNombreVariedad($idVariedad) {
        $query = $this->db->prepare("SELECT nombre FROM variedades WHERE id_variedad = ?");
        $query->execute([$idVariedad]);
    
        // Obtener el resultado y devolverlo
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['nombre'] : null;
    }
    public function agregarVariedad($nombre, $precio) {
        $stmt = $this->db->prepare("INSERT INTO variedades (nombre, precio_x_litro) VALUES (UPPER(?), ?)");
        $stmt->execute([$nombre, $precio]);
        // Verifica si la inserción fue exitosa
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function modificarPrecio($id_variedad, $precio) {
        $stmt = $this->db->prepare("UPDATE variedades SET precio_x_litro = ? WHERE id_variedad = ?");
        $stmt->execute([$precio, $id_variedad]);
    }
    public function eliminarVariedad($id_variedad) {
        $stmt = $this->db->prepare("DELETE FROM variedades WHERE id_variedad = ?");
        $stmt->execute([$id_variedad]);
    }

    public function getPrecioPorLitro($id_variedad) {
        $query = $this->db->prepare('SELECT precio_x_litro FROM variedades WHERE id_variedad = ?');
        $query->execute([$id_variedad]);
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['precio_x_litro'] : null;
    }
    
    


    
}