<?php
class Database {
    private $host = "localhost";
    private $dbname = "delpechcerveceria";
    private $username = "root";  // Ajusta según tu configuración
    private $password = "";
    public $db;

    public function connect() {
        $this->db = null;
        try {
            $this->db = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        return $this->db;
    }
}
?>
