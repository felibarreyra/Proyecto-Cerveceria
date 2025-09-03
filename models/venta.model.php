<?php
require_once __DIR__ . '/../config/database.php';

class Venta {
    private $db;

    public function __construct() {
        try {
            $database = new Database();
            $this->db = $database->connect();
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function registrarVenta($codigo, $litros, $id_lugar, $id_variedad, $precio = 0) {
        $sql = "INSERT INTO ventas (codigo_barril, litros, id_lugar, id_variedad, precio_total, fecha_venta) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$codigo, $litros, $id_lugar, $id_variedad, $precio]);
    }
    public function obtenerVentasPorFecha($fecha) {
        $sql = "SELECT v.*, l.nombre AS lugar, var.nombre AS variedad 
                FROM ventas v
                JOIN lugar l ON v.id_lugar = l.id_lugar
                JOIN variedades var ON v.id_variedad = var.id_variedad
                WHERE DATE(v.fecha_venta) = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerVentasConFiltros($fecha_inicio = null, $fecha_fin = null, $variedad = null, $litros = null, $lugar = null) {
        $sql = "SELECT v.*, l.nombre AS lugar, var.nombre AS variedad 
                FROM ventas v
                JOIN lugar l ON v.id_lugar = l.id_lugar
                JOIN variedades var ON v.id_variedad = var.id_variedad
                WHERE 1=1";
        $params = [];

        if ($fecha_inicio && $fecha_fin) {
            $sql .= " AND DATE(v.fecha_venta) BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        } elseif ($fecha_inicio) {
            $sql .= " AND DATE(v.fecha_venta) >= ?";
            $params[] = $fecha_inicio;
        } elseif ($fecha_fin) {
            $sql .= " AND DATE(v.fecha_venta) <= ?";
            $params[] = $fecha_fin;
        }

        if ($variedad) {
            $sql .= " AND v.id_variedad = ?";
            $params[] = $variedad;
        }
        if ($litros) {
            $sql .= " AND v.litros = ?";
            $params[] = $litros;
        }
        if ($lugar) {
            $sql .= " AND v.id_lugar = ?";
            $params[] = $lugar;
        }

        $sql .= " ORDER BY v.fecha_venta DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getVentasAnuales($anio) {
        $sql = "SELECT v.*, l.nombre AS lugar, var.nombre AS variedad 
                FROM ventas v
                JOIN lugar l ON v.id_lugar = l.id_lugar
                JOIN variedades var ON v.id_variedad = var.id_variedad
                WHERE YEAR(v.fecha_venta) = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$anio]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
}

