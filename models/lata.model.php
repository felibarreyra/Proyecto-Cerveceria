<?php
require_once __DIR__ . '/../config/database.php';

class LataModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();  // conexión PDO
    }

    public function getAllLatas() {
        $sql = "SELECT l.id_lata, v.nombre AS variedad, l.cantidad 
                FROM latas l 
                JOIN variedades v ON l.id_variedad = v.id_variedad";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // devuelve array asociativo
    }

    public function getByVariedad($id_variedad) {
        $stmt = $this->db->prepare("SELECT * FROM latas WHERE id_variedad = ?");
        $stmt->execute([$id_variedad]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addOrUpdate($id_variedad, $cantidad) {
        $lata = $this->getByVariedad($id_variedad);
        if ($lata) {
            $stmt = $this->db->prepare("UPDATE latas SET cantidad = cantidad + ? WHERE id_variedad = ?");
            return $stmt->execute([$cantidad, $id_variedad]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO latas (id_variedad, cantidad) VALUES (?, ?)");
            return $stmt->execute([$id_variedad, $cantidad]);
        }
    }
    public function descontarStock($id_variedad, $cantidad) {
        $stmt = $this->db->prepare("UPDATE latas SET cantidad = cantidad - ? WHERE id_variedad = ?");
        return $stmt->execute([$cantidad, $id_variedad]);
    }
    
    public function registrarVenta($id_variedad, $id_lugar, $cantidad, $precio_unitario) {
        $stmt = $this->db->prepare("INSERT INTO ventas_latas (id_variedad, id_lugar, cantidad, precio_unitario, fecha_venta) 
                                    VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$id_variedad, $id_lugar, $cantidad, $precio_unitario]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function eliminarLata($id_lata) {
        $stmt = $this->db->prepare("DELETE FROM latas WHERE id_lata = ?");
        return $stmt->execute([$id_lata]);
    }
    public function getVentasPorClienteYFecha($id_lugar, $fecha) {
        $stmt = $this->db->prepare("
            SELECT v.cantidad, v.precio_unitario, var.nombre AS variedad
            FROM ventas_latas v
            JOIN variedades var ON v.id_variedad = var.id_variedad
            WHERE v.id_lugar = ? AND DATE(v.fecha_venta) = ?
        ");
        $stmt->execute([$id_lugar, $fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
}
