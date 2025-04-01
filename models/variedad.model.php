<?php
require_once './config/database.php';

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
    


    
}