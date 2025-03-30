<?php
require_once './models/barril.model.php'; // Asegúrate de que el path es correcto

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_barril'])) {
    $id_barril = $_POST['id_barril'];

    $barrilModel = new Barril();
    if ($barrilModel->eliminarBarril($id_barril)) {
        // Redirigir a la página de origen (de donde vino la solicitud)
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : './listar_ventas.php';
        header("Location: $referer"); // Redirige a la página de referencia
        exit();
    } else {
        echo "Error al eliminar el barril.";
    }
}
