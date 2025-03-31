<?php
require_once './models/barril.model.php'; // Asegúrate de que el path es correcto

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['codigo'])) {
    $codigo= $_POST['codigo'];

    $barrilModel = new Barril();
    if ($barrilModel->vaciarBarril($codigo)) {
        // Redirigir a la página de origen (de donde vino la solicitud)
        header("Location: ./listar_barriles_vacios.php"); // Redirige a la página de referencia
        exit();
    } else {
        echo "Error al eliminar el barril.";
    }
}
