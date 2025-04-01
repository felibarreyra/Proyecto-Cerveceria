<?php require_once './models/variedad.model.php';

class variedadController {
    private $variedadModel;

    public function __construct() {
        $this->variedadModel = new Variedad();
    }

    public function getAllVariedades() {
    $variedades = $this->variedadModel->getAllVariedades();
    return $variedades;  
}

    public function addVariedad($nombre){
        if (!empty($nombre)) {
            $this->variedadModel->addVariedad($nombre);
        } else {
            echo "Error: Todos los campos son obligatorios.";
            return;
        }
    }
    public function getNombreVariedad($idVariedad) {
        return $this->variedadModel->getNombreVariedad($idVariedad);
    }
    
}
?>
