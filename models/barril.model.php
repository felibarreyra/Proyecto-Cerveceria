<?php
require_once __DIR__ . '/../config/database.php';

class Barril {
    private $db;

    public function __construct() {
        try {
            $database = new Database();
            $this->db = $database->connect();
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function obtenerTodos() {
        $query = $this->db->prepare("
           SELECT b.id_barril, b.codigo, v.nombre AS variedad, l.nombre AS lugar, b.litros, b.estado, b.fecha_venta 
            FROM barriles b 
            JOIN variedades v ON b.id_variedad = v.id_variedad
            JOIN lugar l ON b.id_lugar = l.id_lugar
            ORDER BY b.codigo ASC

        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function insertarBarril($codigo, $id_variedad, $id_lugar, $litros, $estado) {
        try {
            $query = $this->db->prepare("SELECT COUNT(*) FROM barriles WHERE codigo = :codigo");
            $query->bindParam(':codigo', $codigo);
            $query->execute();
            if ($query->fetchColumn() > 0) {
                echo "Error: El barril con este código ya existe.";
                return false;
            }

            $query = $this->db->prepare("
                INSERT INTO barriles (codigo, id_variedad, id_lugar, litros, estado) 
                VALUES (:codigo, :id_variedad, :id_lugar, :litros, :estado)
            ");
            $query->bindParam(':codigo', $codigo);
            $query->bindParam(':id_variedad', $id_variedad);
            $query->bindParam(':id_lugar', $id_lugar);
            $query->bindParam(':litros', $litros);
            $query->bindParam(':estado', $estado);

            return $query->execute();
        } catch (Exception $e) {
            echo "Error al insertar: " . $e->getMessage();
            return false;
        }
    }

    public function eliminarBarril($id_barril) {
        try {
            $query = $this->db->prepare("DELETE FROM barriles WHERE id_barril = :id_barril");
            $query->bindParam(':id_barril', $id_barril, PDO::PARAM_INT);
            return $query->execute();
        } catch (Exception $e) {
            echo "Error al eliminar: " . $e->getMessage();
            return false;
        }
    }

    // Filtrado solo para barriles en "CAMARA"
    public function getBarrilesFiltradosCamara($variedad = '', $codigo = '', $litros = '') {
        // Inicializamos la consulta
        $query = "SELECT b.id_barril, b.codigo, v.nombre AS variedad, l.nombre AS lugar, b.litros
                  FROM barriles b 
                  JOIN variedades v ON b.id_variedad = v.id_variedad
                  JOIN lugar l ON b.id_lugar = l.id_lugar
                  WHERE l.nombre = 'CAMARA' AND (b.estado = 'LLENO' OR b.estado = 'EN USO')";
    
        // Filtro por variedad
        if ($variedad) {
            $query .= " AND b.id_variedad = :variedad";
        }
    
        // Filtro por código
        if ($codigo) {
            $query .= " AND b.codigo LIKE :codigo";
        }
    
        // Filtro por litros (Asegúrate de que el valor de litros es un valor válido del ENUM)
        if ($litros) {
            // Asegúrate de que el valor de litros sea uno de los valores válidos para el ENUM
            $query .= " AND b.litros = :litros";  // Filtrado exacto de litros basado en el valor del ENUM
        }
    
        $query .= " ORDER BY b.codigo ASC";  // Ordenamos los resultados
    
        $stmt = $this->db->prepare($query);
    
        // Asignamos los parámetros a la consulta
        if ($variedad) {
            $stmt->bindParam(':variedad', $variedad, PDO::PARAM_INT);
        }
        if ($codigo) {
            $codigo = "%" . $codigo . "%";  // Para búsqueda parcial
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        }
        if ($litros) {
            $stmt->bindParam(':litros', $litros, PDO::PARAM_STR);  // Es importante usar PDO::PARAM_STR para el ENUM
        }
    
        // Ejecutamos la consulta
        $stmt->execute();
    
        // Retornamos los resultados
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    

    // Filtrado para todos los barriles excepto los de "CAMARA"
    public function getBarrilesFiltradosVentas($variedad = '', $codigo = '', $litros = '', $lugar = '') {
        // Iniciar la consulta base
        $query = "SELECT b.*, v.nombre AS variedad, l.nombre AS lugar, b.fecha_venta
                  FROM barriles b
                  JOIN variedades v ON b.id_variedad = v.id_variedad
                  JOIN lugar l ON b.id_lugar = l.id_lugar
                  WHERE l.nombre != 'CAMARA' AND b.estado!='VACIO'"; // Excluir los barriles de "CAMARA"
    
        // Agregar condiciones de filtrado según los parámetros recibidos
        if ($variedad) {
            $query .= " AND b.id_variedad = :variedad";
        }
        if ($codigo) {
            $query .= " AND b.codigo LIKE :codigo";
        }
        if ($litros) {
            $query .= " AND b.litros = :litros";
        }
        if ($lugar) {
            $query .= " AND b.id_lugar = :lugar";
        }
    
        // Preparar la consulta
        $stmt = $this->db->prepare($query);
    
        // Enlazar los parámetros si son proporcionados
        if ($variedad) {
            $stmt->bindParam(':variedad', $variedad, PDO::PARAM_INT);
        }
        if ($codigo) {
            $codigo = "%$codigo%"; // Permitimos búsquedas parciales
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        }
        if ($litros) {
            $stmt->bindParam(':litros', $litros, PDO::PARAM_STR);  // Es importante usar PDO::PARAM_STR para el ENUM
        }
        if ($lugar) {
            $stmt->bindParam(':lugar', $lugar, PDO::PARAM_INT);
        }
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Obtener los resultados
        $barriles = $stmt->fetchAll(PDO::FETCH_OBJ);
    
        return $barriles;
    }
    
    public function getEstadoByCodigo($codigo) {
        // Preparar la consulta utilizando un marcador de posición para evitar inyección SQL
        $query = $this->db->prepare("SELECT estado FROM barriles WHERE codigo = :codigo");
        
        // Ejecutar la consulta pasando el parámetro del código
        $query->execute([':codigo' => $codigo]);
        
        // Obtener el resultado como un objeto
        $resultado = $query->fetch(PDO::FETCH_OBJ);
        
        // Retornar el estado si se encuentra, de lo contrario null
        return $resultado ? $resultado->estado : null;
    }

    public function getBarrilByCodigo($codigo) {
        $sql = "SELECT * FROM barriles WHERE codigo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function modificarBarril($id_barril, $codigo, $id_variedad, $id_lugar, $litros, $estado, $fecha_venta) {
        $sql = "UPDATE barriles SET codigo = ?, id_variedad = ?, id_lugar = ?, litros = ?, estado = ?, fecha_venta=? WHERE id_barril = ?";
        $stmt = $this->db->prepare($sql);
    
        return $stmt->execute([$codigo, $id_variedad, $id_lugar, $litros, $estado, $fecha_venta, $id_barril]);
    }
    
    
    public function obtenerBarrilesPorEstado($estado) {
        $sql = "SELECT * FROM barriles WHERE estado = :estado";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->execute();

        // Recuperar los barriles y devolverlos
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function getBarrilesFiltro($estado, $litros = null) {
        $query = "SELECT * FROM barriles WHERE estado = :estado";
        
        if ($litros) {
            $query .= " AND litros = :litros";
        }
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":estado", $estado);
    
        if ($litros) {
            $stmt->bindParam(":litros", $litros);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function obtenerBarrilPorId($id_barril) {
        $sql = "SELECT * FROM barriles WHERE id_barril = :id_barril";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_barril', $id_barril, PDO::PARAM_INT);
        $stmt->execute();

        // Recuperar y devolver el barril (como objeto)
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    // Obtener un barril por código
    public function obtenerBarrilPorCodigo($codigo) {
        $sql = "SELECT * FROM barriles WHERE codigo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$codigo]);
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
   // Model: obtenerNombreLugar
public function obtenerNombreLugar($id_lugar) {
    try {
        $sql = "SELECT nombre FROM lugar WHERE id_lugar = :id_lugar LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_lugar', $id_lugar, PDO::PARAM_INT);

        // Ejecutamos la consulta
        $stmt->execute();

        // Comprobamos si se encontró el lugar
        $lugar = $stmt->fetch(PDO::FETCH_ASSOC);
        return $lugar ? $lugar['nombre'] : null; // Si no hay lugar, retorna null
    } catch (PDOException $e) {
        // Si hay un error de base de datos, lo mostramos (en producción, usa un sistema de logs)
        echo 'Error: ' . $e->getMessage();
        return null; // Retorna null en caso de error
    }
}


    // Modificar el barril por código
    public function modificarBarrilPorCodigo($codigo, $id_variedad, $id_lugar, $litros, $estado, $fecha_venta) {
        $sql = "UPDATE barriles SET id_variedad = ?, id_lugar = ?, litros = ?, estado = ?, fecha_venta=? WHERE codigo = ?";
        $stmt = $this->db->prepare($sql); 
        return $stmt->execute([$id_variedad, $id_lugar, $litros, $estado, $fecha_venta, $codigo]);
    }
    
    public function getFechaByCodigo($codigo) {
        $sql = "SELECT fecha_venta FROM barriles WHERE codigo = :codigo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $resultado ? $resultado->fecha_venta : null;
    }
    function vaciarBarril($codigo) {
        // Verificar si el código es válido
        if (empty($codigo)) {
            return false;  // Si el código es vacío, retornar false
        }
        
        // Consulta SQL para actualizar el estado del barril
        $sql = "UPDATE barriles SET estado='VACIO', fecha_venta=NULL WHERE codigo=?";
        
        // Preparar la consulta
        $stmt = $this->db->prepare($sql);
    
        // Ejecutar la consulta y verificar si tuvo éxito
        if ($stmt->execute([$codigo])) {
            return true;  // Si la consulta se ejecutó correctamente, retornar true
        } else {
            return false; // Si hubo un error en la ejecución, retornar false
        }
    }
    public function getBarrilesPorClienteYFecha($cliente, $fecha) {
        $sql = "
            SELECT 
                barril.codigo, 
                barril.litros, 
                variedades.id_variedad,
                variedades.nombre AS variedad, 
                variedades.precio_x_litro
            FROM barriles AS barril
            INNER JOIN variedades ON barril.id_variedad = variedades.id_variedad
            WHERE barril.id_lugar = :cliente 
            AND DATE(barril.fecha_venta) = :fecha
        ";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cliente', $cliente, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    
        return [];
    }
    
    public function getVariedadPorId($id_variedad) {
        // Suponiendo que estás utilizando PDO para la base de datos
        $sql = "SELECT * FROM variedades WHERE id_variedad = :id_variedad";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_variedad', $id_variedad, PDO::PARAM_INT);
        $stmt->execute();
    
        // Si se encuentra la variedad, devolverla, de lo contrario devolver false
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    // Método del controlador BarrilController
public function obtenerBarrilesEnCamaraPorFechas($fecha_inicio, $fecha_fin) {
    $sql = "SELECT * FROM barriles WHERE fecha_venta BETWEEN ? AND ? AND id_lugar = 1";
    $query = $this->db->prepare($sql);
    $query->execute([$fecha_inicio, $fecha_fin]);
    return $query->fetchAll(PDO::FETCH_OBJ);
}

    
    
}
?>
